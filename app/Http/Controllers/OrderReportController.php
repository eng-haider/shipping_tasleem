<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderReport\OrderReport;
use App\Http\Repositories\OrderReportRepository;

class OrderReportController extends Controller
{
    public function __construct(private OrderReportRepository $OrderReportRepo)
    {
        $this->middleware(['permissions:order-status-report'])->only(['getOrderStatus']);
        $this->middleware(['permissions:order-driver-report'])->only(['getOrderDriver']);
        $this->middleware(['permissions:order-company-report'])->only(['getOrderCompany']);
        $this->middleware(['permissions:order-governorate-report'])->only(['getOrderGovernorate']);
    }
    
    //Order Status Report
    public function getOrderStatus(OrderReport $request)
    {
        $data = $request->validated();
        return $this->OrderReportRepo->getOrderStatus($data, $request->company_id);
    }
    //Order Driver Report
    public function getOrderDriver(OrderReport $request)
    {
        $data = $request->validated();
        return $this->OrderReportRepo->getOrderDriver($data);
    }
    //Order Company Report
    public function getOrderCompany(OrderReport $request)
    {
        $data = $request->validated();
        return $this->OrderReportRepo->getOrderCompany($data);
    }
    //Order Governorate Report
    public function getOrderGovernorate(OrderReport $request)
    {
        $data = $request->validated();
        return $this->OrderReportRepo->getOrderGovernorate($data);
    }
}