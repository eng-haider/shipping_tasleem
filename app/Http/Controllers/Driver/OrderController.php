<?php

namespace App\Http\Controllers\Driver;

use App\Helper\Helper;
use App\Models\Status;
use App\Http\Requests\Order\Create;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\History;
use App\Services\CDCIntegrateService;
use App\Http\Requests\Order\RateOrder;
use App\Http\Requests\Index\MyOrderParams;
use App\Http\Repositories\StatusRepository;
use App\Http\Requests\Order\DriverChangeStatus;
use App\Http\Repositories\Driver\OrderRepository;

class OrderController extends Controller
{
    public function __construct(private OrderRepository $orderRepo, private StatusRepository $statusRepo)
    {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MyOrderParams $request)
    {
        $request->validated();
        $statuses = $request->statuses? $request->statuses:[];
        return $this->orderRepo->myOrderList($request->take, $request->from, $request->to, $statuses);
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param  \Illuminate\Http\Order\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request, History $history)
    {
        $order = $request->validated();
        $history = $history->validated(); 
        $checkOrderExists = $this->orderRepo->showByTr($order['tr']);
        if($checkOrderExists){
            if($request->hasFile('fingerprint_image')){
                $history['fingerprint_image'] = $request->file('fingerprint_image')->store('orders-history-fingerprint');
            }
            if($request->hasFile('image')){
                $history['image'] = $request->file('image')->store('orders-history-images');
            }
            return $this->reassignOrder($history, $order['tr']);
        }
        $service = new CDCIntegrateService();
        $check = $service->checkOrderTrExistsInCDC($order['tr']);
        if(!$check)
            return Helper::responseError('This tr not found', [], 400);
        if($request->hasFile('image')){
            $history['image'] = $request->file('image')->store('orders-history-images');
        }
        if($request->hasFile('fingerprint_image')){
            $history['fingerprint_image'] = $request->file('fingerprint_image')->store('orders-history-fingerprint');
        }
        unset($order['image']);unset($order['fingerprint_image']);
        return $this->orderRepo->createOrder($order, $history, $check);
    }
    /**
     * created Order in storage.
     *
     * @param  \Illuminate\Http\Order\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function createOrder($order, $history, $check)
    { 
        if($order->hasFile('image')){
            $history['image'] = $order->file('image')->store('orders-history-images');
        }
        if($order->hasFile('fingerprint_image')){
            $history['fingerprint_image'] = $order->file('fingerprint_image')->store('orders-history-fingerprint');
        }
        unset($order['image']);unset($order['fingerprint_image']);
        return $this->orderRepo->createOrder($order, $history, $check);
    }

    /**
     * Display the Order.
     *
     * @param  \App\Models\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($tr)
    {
        $order = $this->orderRepo->showByTr($tr);
        if(!$order){
            return Helper::responseError('model not found', [], 404);
        }
        $orders = $this->orderRepo->showByUuid($order->uuid);
        return Helper::responseSuccess('Get Order Successfully', $orders);
    }

    /**
     * Reassign Order to Driver.
     *
     * @param  \Illuminate\Order\Update  $request
     * @param  \App\Models\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function reassignOrder($assign, $tr){
        $order = $this->orderRepo->showByTr($tr);
        if(!$order){
            return Helper::responseError('model not found', [], 404);
        }
        if($order->status->name != 'Returned'){
            return Helper::responseError('You cann`t reassign this order', [], 400);
        }
        $status = $this->statusRepo->getReceivedStatus();
        $service = new CDCIntegrateService();
        $changeCDCOrderStatus = $service->changeCDCOrderStatus($order, $status->id, $order['tr']);
        if(!$changeCDCOrderStatus)
            return Helper::responseError('Failed to change status CDC', [], 400);
        return $this->orderRepo->reassignOrder($order->uuid, $assign);
    }

    /**
     * Add Rate To Order.
     *
     * @param  \Illuminate\Order\Update  $request
     * @param  \App\Models\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function addRate(RateOrder $request, $tr){
        $assign = $request->validated();
        $order = $this->orderRepo->showByTr($tr);
        if(!$order){
            return Helper::responseError('model not found', [], 404);
        }
        if($order->rate){
            return Helper::responseError('order has already rated', [], 400);
        }
        if($order->status->name != 'Delivered'){
            return Helper::responseError('You cann`t Rate this order, because it is not delivered', [], 400);
        }
        return $this->orderRepo->addRate($order->uuid, $assign);
    }
    
    /**
     * Change Order Status.
     *
     * @param  \Illuminate\Order\Update  $request
     * @param  \App\Models\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(DriverChangeStatus $request,$tr)
    {
        $history = $request->validated();
        $order = $this->orderRepo->showByTr($tr);
        if(!$order){
            return Helper::responseError('model not found', [], 404);
        }
        if($order->status->name == 'Returned'){
            if($request->hasFile('fingerprint_image')){
                $history['fingerprint_image'] = $request->file('fingerprint_image')->store('orders-history-fingerprint');
            }
            if($request->hasFile('image')){
                $history['image'] = $request->file('image')->store('orders-history-images');
            }
            $this->orderRepo->reassignOrder($order->uuid, $history);
        }
        if($order->driver_id != auth('driver')->user()->id){
            return Helper::responseError('You are not allowed to change status for this order', [], 403); 
        }
        if($this->orderRepo->checkOrderStatusName($order->uuid, 'Delivered')){
            return Helper::responseError('This order is already delivered', [], 400);
        }
        $status['status_id'] = $history['status_id'];
        $status['long'] = $history['long'];
        $status['lat'] = $history['lat'];
        
        $status['company_id'] = auth('driver')->user()->company_id;
        $status['governorate_id'] = auth('driver')->user()->governorate_id;

        $history['notes'] = $request->has('notes') ? $history['notes'] : null;
        $history['order_id'] = $order->uuid;
        $orderAPI = $order;
        $orderAPI['notes'] = $request->has('notes') ? $history['notes'] : null;
        $statusName = Status::findOrFail($status['status_id']);
        if(($order->driver_id != auth('driver')->user()->id) && $statusName->name != 'Returned'){
            return Helper::responseError('You are not allowed to change status for this order', [], 403); 
        }
        // if($statusName->name != 'Delayed'){
            $service = new CDCIntegrateService();
            $changeCDCOrderStatus = $service->changeCDCOrderStatus($orderAPI, $status['status_id'], $tr);
            if(!$changeCDCOrderStatus)
                return Helper::responseError('Failed to change status CDC', [], 400);
        // }
        if($order->status_id == $status['status_id'])
            return Helper::responseError('This status is already exists for this order', [], 400);
        if(isset($history['rate']))
            $this->orderRepo->update($order->id, [
                'rate' => $request->rate
            ]);
            unset($history['rate']);
        return $this->orderRepo->changeStatus($order->uuid, $status, $history);
    }
}
