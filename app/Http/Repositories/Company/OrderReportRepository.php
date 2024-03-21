<?php
namespace App\Http\Repositories\Company;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderReportRepository{
    public function getOrderStatus($filter){
        return Order::whereBetween('created_at', [$filter['from_date'], $filter['to_date']])
            ->where('company_id', auth('user')->user()->company->company_id)
            ->select('status_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('status_id')
            ->with('status')
            ->get();
    }  
    public function getOrderDriver($filter){
        return Order::whereBetween('created_at', [$filter['from_date'], $filter['to_date']])
            ->where('company_id', auth('user')->user()->company->company_id)
            ->select('driver_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('driver_id')
            ->with('driver')
            ->get();
    } 
    public function getOrderCompany($filter){
       return Order::whereBetween('created_at', [$filter['from_date'], $filter['to_date']])
            ->where('company_id', auth('user')->user()->company->company_id)
            ->select('company_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('company_id')
            ->with('company')
            ->get();
    }
    public function getOrderGovernorate($filter){
       return Order::whereBetween('created_at', [$filter['from_date'], $filter['to_date']])
            ->where('company_id', auth('user')->user()->company->company_id)
            ->select('governorate_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('governorate_id')
            ->with('governorate')
            ->get();
    }
}