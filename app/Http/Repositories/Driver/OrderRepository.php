<?php
namespace App\Http\Repositories\Driver;

use App\Models\Order;
use App\Models\IssuanceCenter;

//IssuanceCenter
use App\Helper\Helper;
use App\Models\Customer;
use PHPUnit\TextUI\Help;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\DB;
use App\Services\CDCIntegrateService;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Repositories\BaseRepository;
use App\Http\Repositories\StatusRepository;
use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\IssuanceCenterRepository;


use App\Http\Repositories\OrderHistoryRepository;

class OrderRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Order());
    }
    public function showByTr($tr)
    {
        return $this->model->where('tr', $tr)->with('status')->first();
    }


    // public function showByUuid($uuid)
    // {
    //     return $this->model->where('uuid', $tr)->with('status')->first();
    // }
    public function myOrderList($take = 10, $from = null, $to = null, $statuses = [])
    {
        $result = QueryBuilder::for($this->model)
                                ->where('driver_id', auth('driver')->user()->id)
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
                                if($from && $to)
                                $result->whereDate('updated_at', '>=', $from)->whereDate('updated_at', '<=', $to);
                                if(count($statuses) > 0)
                                    $result->whereIn('status_id', $statuses);
                                return $result->paginate($take);
    }
    public function createOrder($order, $history, $check)
    {

   
        $statusRepo = new StatusRepository();
        $customerRepo = new CustomerRepository();
        $issuancecenterRepo = new IssuanceCenterRepository();

        //IsuanceCenterRepository

        $orderHistoryRepo = new OrderHistoryRepository();
        DB::beginTransaction();
        try{
            $status = $statusRepo->getReceivedStatus();
            $order['company_id'] = auth('driver')->user()->company_id;
            $order['governorate_id'] = auth('driver')->user()->governorate_id;
            // $order['bn'] = $check['bn'];
            $order['nd'] = $check['nd'];
            $order['delivery_service_provider_id'] = $check['company_id'];
            $order['status_id'] = $status['id'];








            //store issuancecenter and customers

            // $issuancecenterModel = IssuanceCenter::where('name', $check['issuancecenter']['name'])
            // ->where('governorate', $check['issuancecenter']['governorates']['name']) ->first();

        //    if(!$issuancecenterModel){

        //     // $issuancecenter['phone'] = (string) $check['issuancecenter']['phone'];
        //     $issuancecenter['name'] = (string)$check['issuancecenter']['name'];
        //     $issuancecenter['address'] = (string)$check['issuancecenter']['address'];
        //     $issuancecenter['governorate'] = (string)$check['issuancecenter']['governorates']['name'];

        //     $issuancecenterModel = $issuancecenterRepo->create($issuancecenter);
        //    }

            // $customer['issuance_center_id']=$issuancecenterModel->id;
            // $order['issuance_center_id']=$issuancecenterModel->id;

            $custumers=[];
            foreach($check['custumers'] as $value){

                $customerModel = Customer::where('phone', $value['phone'])->where('name', $value['name'])
                ->where('issuance_center_name', $check['issuancecenter']['name'])
                ->first();
                if(!$customerModel){
                    $customer['phone'] = (string) $value['phone'];
                    $customer['name'] = (string)$value['name'];
                    $customer['address'] = (string)$check['issuancecenter']['address'];

                    $customer['issuance_center_name'] = (string) $check['issuancecenter']['name'];
                    $customer['issuance_center_address'] = (string)$check['issuancecenter']['address'];
                    $customer['issuance_center_governorate'] = (string)$check['issuancecenter']['governorates']['name'];

                    $customerModel = $customerRepo->create($customer);
                 
                }else{
                    array_push($custumers, $customerModel->id);
                }
              
            }
            





        

            unset($order['customer_address']);unset($order['customer_name']);unset($order['customer_phone']);
            // $order['customer_id'] = $customerModel->id;
            $response = $this->model->create($order);

            $response->custumers()->attach($custumers);

            $history['order_id'] = $response['uuid'];
            $history['status_id'] = $status['id'];
            $createOrderHistory = $orderHistoryRepo->create($history);

            $service = new CDCIntegrateService();
             $changeCDCOrderStatus = $service->changeCDCOrderStatus($response, $status['id'], $response['tr']);
            if(!$changeCDCOrderStatus)
                return Helper::responseError('Failed to change status CDC', [], 400);

            DB::commit();
            return Helper::responseSuccess('Order created successfully', [
                'order' => $response,
                'history' => $createOrderHistory,
                'customer' =>$response->custumers
            ]);
        }catch(\Exception $e){
            DB::rollBack();
            return Helper::responseError($e->getMessage());
        }
    }
    public function changeStatus($uuid, $status, $history){
        DB::beginTransaction();
        try{
            $order = $this->model->where('uuid', $uuid)->firstOrFail();
            if(isset($history['image'])){
                $history['image'] = Helper::uploadImage($history['image'], 'orders-history');
            }
            if(isset($history['fingerprint_image'])){
                $history['fingerprint_image'] = Helper::uploadImage($history['fingerprint_image'], 'orders-history-fingerprint');
            }
            $order->update($status);
            $history['driver_id'] = auth('driver')->user()->id;
            OrderHistory::create($history);
            DB::commit();
            return Helper::responseSuccess('Order Status updated successfully', $order);
        }catch(\Exception $e){
            DB::rollBack();
            return Helper::responseError($e->getMessage());
        }
    }

    public function checkOrderStatusExists($uuid, $statusId)
    {
        return $this->model->where('uuid', $uuid)->where('status_id', $statusId)->exists();
    }
    public function checkOrderStatusName($uuid, $statusName = 'Delivered')
    {
        $order = $this->model->where('uuid', $uuid)->with('status')->firstOrFail();
        if($order->status->name == $statusName)
            return true;
    }
    public function reassignOrder($uuid, $data)
    {
        DB::beginTransaction();
        try{
            $statusRepo = new StatusRepository();
            $status = $data;
            $status['status_id'] = $statusRepo->getReceivedStatus()->id;
            $status['driver_id'] = auth('driver')->user()->id;
            
            $status['company_id'] = auth('driver')->user()->company_id;
            $status['governorate_id'] = auth('driver')->user()->governorate_id;
            

            unset($status['fingerprint'], $status['fingerprint_image'], $status['image']);
            $order = $this->model->where('uuid', $uuid)->firstOrFail();
            $order->update($status);
            $history = $data;
            $history['driver_id'] = auth('driver')->user()->id;
            $history['order_id'] = $uuid;
            // $history['company_id'] = auth('driver')->user()->company_id;
            $history['status_id'] = $statusRepo->getReceivedStatus()->id;
            OrderHistory::create($history);
            DB::commit();
            return Helper::responseSuccess('Order reassign successfully', $order);
        }catch(\Exception $e){
            DB::rollBack();
            return Helper::responseError($e->getMessage());
        }
    }
    public function addRate($uuid, $data)
    {
        $order = $this->model->where('uuid', $uuid)->firstOrFail();
        if($order->driver_id != auth('driver')->user()->id){
            return Helper::responseError('You are not allowed to add rate for this order', [], 403); 
        }
        
        $order->update($data);
        return Helper::responseSuccess('Rate added successfully', $order);
    }
}