<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\OrderHistoryRepository;

class OrderHistoryController extends Controller
{
    public function __construct(private OrderHistoryRepository $OrderHistoryRepo)
    {
        $this->middleware(['permissions:get-orderHistory'])->only(['index']);
        $this->middleware(['permissions:show-orderHistory'])->only(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->OrderHistoryRepo->getList($request->take);
    }

    /**
     * Display the OrderHistory.
     *
     * @param  \App\Models\Models\OrderHistory  $orderHistory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->OrderHistoryRepo->show($id);
        return Helper::responseSuccess('Get order history successfully', $response);
    }
}
