<?php

namespace App\Http\Controllers\Driver;

use App\Services\SendNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderReport\OrderReport;
use App\Http\Repositories\Driver\OrderReportRepository;

class OrderReportController extends Controller
{
    public function __construct(private OrderReportRepository $OrderReportRepo)
    {}
    
    //Order Status Report
    public function getOrderStatus(OrderReport $request)
    {
        $data = $request->validated();
        return $this->OrderReportRepo->getOrderStatus($request->from_date, $request->to_date);
    }
}