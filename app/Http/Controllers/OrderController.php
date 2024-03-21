<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Helper\Helper;
use App\Exports\OrderExport;
use App\Http\Requests\Index\Params;
use App\Http\Requests\Order\Update;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\CDCIntegrateService;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\DriverRepository;
use App\Http\Repositories\StatusRepository;
use App\Http\Requests\Order\UserChangeStatus;
use App\Http\Requests\Order\OrderExportParams;
use App\Http\Repositories\OrderHistoryRepository;

class OrderController extends Controller
{
    public function __construct(private OrderRepository $orderRepo, private OrderHistoryRepository $orderHistoryRepo, private StatusRepository $statusRepo, private DriverRepository $driverRepo)
    {
        $this->middleware(['permissions:get-order'])->only(['index']);
        $this->middleware(['permissions:store-order'])->only(['store']);
        $this->middleware(['permissions:show-order'])->only(['show']);
        $this->middleware(['permissions:update-order'])->only(['update']);
        $this->middleware(['permissions:delete-order'])->only(['destroy']);
        $this->middleware(['permissions:change-status-order'])->only(['changeStatus']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Params $request)
    {
        $request->validated();
        return $this->orderRepo->getOrderList($request->take, $request->from, $request->to);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportOrderExcil(OrderExportParams $request)
    {
        $request->validated();
        return $this->orderRepo->exportOrders($request->take, $request->from, $request->to, $request->columns);
    }

    /**
     * Display the Order.
     *
     * @param  \App\Models\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->orderRepo->show($id);
        return Helper::responseSuccess('Get Order Successfully', $response);
    }

    /**
     * Update the Order in storage.
     *
     * @param  \Illuminate\Order\Update  $request
     * @param  \App\Models\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $uuid)
    {
        $orderModel = Order::where('uuid', $uuid)->firstOrFail();
        $order = $request->validated();
        $response = $this->orderRepo->update($orderModel->id, $order);
        return Helper::responseSuccess('Update Order Successfully', $response);
    }

    /**
     * Remove the Order storage.
     *
     * @param  \App\Models\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if($order->status()->name == 'Inprogress' || $order->status()->name == 'Pending'){
            $response = $this->orderRepo->delete($order);
            return Helper::responseSuccess('Delete Order Successfully', $response);
        }
        return Helper::responseError('You Can`t delete this order', [], 400);
    }

    /**
     * Update the Order in storage.
     *
     * @param  \Illuminate\Order\Update  $request
     * @param  \App\Models\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(UserChangeStatus $request, $uuid)
    {
        // $status = $request->validated();
        // $orderAPI = $this->orderRepo->showByUuid($uuid);
        // $orderAPI['notes'] = $request->has('notes') ? $status['notes'] : null;
        // $service = new CDCIntegrateService();
        // $service->changeCDCOrderStatus($orderAPI, $status['status_id']);
        // if($this->orderRepo->checkOrderStatusExists($uuid, $status['status_id']))
        //     return Helper::responseError('This status is already exists for this order', [], 400);
        // $response = $this->orderRepo->changeStatus($uuid, $status);
        // return response()->json($response[0], $response[1]);
    }
}
