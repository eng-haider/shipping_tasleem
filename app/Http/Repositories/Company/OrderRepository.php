<?php
namespace App\Http\Repositories\Company;

use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Repositories\BaseRepository;

class OrderRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Order());
    }
    
    //Get all resources
    public function getMyCompanyOrderList($take = 10, $from = null, $to = null)
    {
        $result = QueryBuilder::for($this->model)
                                ->where('company_id', auth('user')->user()->company->company_id);
                                if($from && $to){
                                //  $result->betweenDates($from, $to);
                                    $result->whereDate('updated_at', '>=', $from)->whereDate('updated_at', '<=', $to);
                                }
                                $result->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
        return $result->paginate($take);
    } 

    //Display one resource
    public function show($id)
    {
        return QueryBuilder::for($this->model)
                            ->where('company_id', auth('user')->user()->company->company_id)
                            ->allowedIncludes($this->model->relations)
                            ->findOrFail($id);
    }

    public function changeStatus($uuid, $status){
        DB::beginTransaction();
        try{
            $order = $this->model->where('uuid', $uuid)->firstOrFail();
            $history['notes'] = $status['notes'];
            unset($status['notes']);
            $order->update($status);
            $history['order_id'] = $uuid;
            $history['user_updator_id'] = auth('user')->user()->id;
            OrderHistory::create($history);
            DB::commit();
            return [[
                'status' => true,
                'message' => 'Status Updated Successfully',
                'data' => $order
            ], 200];
        }catch(\Exception $e){
            DB::rollBack();
            return [[
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400];
        }
    }

    public function checkOrderStatusExists($uuid, $statusId)
    {
        return $this->model->where('uuid', $uuid)->where('status_id', $statusId)->exists();
    }
    public function checkOrderStatusIsDelivered($uuid)
    {
        $order = $this->model->where('uuid', $uuid)->with('status')->firstOrFail();
        if($order->status->name == 'Delivered')
            return true;
    }
}