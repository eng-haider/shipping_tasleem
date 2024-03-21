<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderReport\OrderReport;
use App\Http\Repositories\Company\OrderReportRepository;

class OrderReportController extends Controller
{
    public function __construct(private OrderReportRepository $OrderReportRepo)
    {
        $this->middleware(['permissions:compay-order-status-report'])->only(['getOrderStatus']);
        $this->middleware(['permissions:compay-order-driver-report'])->only(['getOrderDriver']);
        $this->middleware(['permissions:compay-order-company-report'])->only(['getOrderCompany']);
        $this->middleware(['permissions:compay-order-governorate-report'])->only(['getOrderGovernorate']);
    }
    
    //Order Status Report
    public function getOrderStatus(OrderReport $request)
    {
        $data = $request->validated();
        return $this->OrderReportRepo->getOrderStatus($data);
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