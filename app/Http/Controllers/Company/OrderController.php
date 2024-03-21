<?php

namespace App\Http\Controllers\Company;

use App\Models\Order;
use App\Helper\Helper;
use App\Http\Requests\Index\Params;
use App\Http\Controllers\Controller;
use App\Services\CDCIntegrateService;
use App\Http\Requests\Order\UserChangeStatus;
use App\Http\Requests\Order\UserChangeStatusMultiple;
use App\Models\Status;

use App\Http\Repositories\Company\OrderRepository;

class OrderController extends Controller
{

    public function __construct(private OrderRepository $orderRepo)
    {
        $this->middleware(['permissions:company-get-order'])->only(['index']);
        $this->middleware(['permissions:company-show-order'])->only(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Params $request)
    {
        $request->validated();
        return $this->orderRepo->getMyCompanyOrderList($request->take, $request->from, $request->to);
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
    public function changeStatusmultiple(UserChangeStatusMultiple $request)
    {


//         $status = $request->validated();
// $errors = []; // Array to store all errors

// $statusName = Status::findOrFail($status['status_id']);
// $service = new CDCIntegrateService();

// foreach ($status['orders'] as $order) {
//     try {
//         $orderAPI = $this->orderRepo->showByUuid($order);

//         // Update order notes if provided in the request
//         $orderAPI['notes'] = $request->has('notes') ? $status['notes'] : null;
//         $status['notes'] = $request->has('notes') ? $status['notes'] : null;

//         if ($statusName->name != 'Delayed') {
//             $changeCDCOrderStatus = $service->changeCDCOrderStatus($orderAPI, $status['status_id'], $orderAPI['tr']);
//             if (!$changeCDCOrderStatus) {
//                 $errors[] = [
//                     'order' => $orderAPI,
//                     'message' => 'Failed to change status CDC'
//                 ];
//                 continue; // Continue to the next iteration
//             }
//         }

//         // Check if the status already exists for this order
//         if ($this->orderRepo->checkOrderStatusExists($order, $status['status_id'])) {
//             $errors[] = [
//                 'order' => $orderAPI,
//                 'message' => 'This status already exists for this order'
//             ];
//             continue; // Continue to the next iteration
//         }

//         unset($status['orders']);
//         $response = $this->orderRepo->changeStatus($order, $status);
//     } catch (\Exception $e) {
//         // If an exception occurs during processing, add the error to the errors array
//         $errors[] = [
//             'order' => $orderAPI ?? null, // Include $orderAPI if available, otherwise null
//             'message' => $e->getMessage()
//         ];
//     }
// }

// // Return the array of errors
// return response()->json($errors);

       
    }
    public function changeStatus(UserChangeStatus $request, $uuid)
    {
        $status = $request->validated();
        if($this->orderRepo->checkOrderStatusIsDelivered($uuid)){
            return Helper::responseError('This order is already delivered', [], 400);
        }
        $orderAPI = $this->orderRepo->showByUuid($uuid);
        $orderAPI['notes'] = $request->has('notes') ? $status['notes'] : null;
        $service = new CDCIntegrateService();
        $changeCDCOrderStatus = $service->changeCDCOrderStatus($orderAPI, $status['status_id'], $orderAPI['tr']);
        if(!$changeCDCOrderStatus)
            return Helper::responseError('Failed to change status CDC', [], 400);
        if($this->orderRepo->checkOrderStatusExists($uuid, $status['status_id']))
            return Helper::responseError('This status is already exists for this order', [], 400);
            $response = $this->orderRepo->changeStatus($uuid, $status); 
        return response()->json($response[0], $response[1]);
    }
    //delete order
    public function destroy(Order $order)
    {
        $service = new CDCIntegrateService();
        if($order->company_id != auth('user')->user()->company->company_id)
            return Helper::responseError('Order not found', [], 404);
        $changeCDCOrderStatus = $service->checkOrderExistsInCDC($order->tr);
        if($changeCDCOrderStatus)
            return Helper::responseError('This order already exists in CDC', [], 400);
        $response = $this->orderRepo->delete($order);
            return Helper::responseSuccess('Order deleted successfully', $response);
    }
    
}
