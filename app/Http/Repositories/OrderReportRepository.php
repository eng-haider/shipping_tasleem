<?php
namespace App\Http\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
class OrderReportRepository{
    
    public function getOrderStatus($filter, $company_id = null){
        $allStatuses = Status::select('id', 'name', 'color', 'image');
        $report = Order::
        whereDate('created_at', '>=', $filter['from_date'])
        ->whereDate('created_at', '<=', $filter['to_date'])
        ->select('status_id', DB::raw('COUNT(*) as order_count'))
        ->groupBy('status_id');
        if($company_id) {
                $report->where('company_id', $company_id);
        }
        $result = $allStatuses
        ->leftJoinSub($report, 'order_counts', function ($join) {
                $join->on('statuses.id', '=', 'order_counts.status_id');
        })
        ->addSelect(DB::raw('COALESCE(order_counts.order_count, 0) as order_count'))
        ->get();
        return $result->map(function ($item) {
                return [
                'status_id' => $item->id,
                'order_count' => $item->order_count,
                'status' => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'color' => $item->color,
                        'image' => $item->image,
                ]
                ];
        });

    }  
    public function getOrderDriver($filter){

       return Order::whereDate('created_at', '>=', $filter['from_date'])
             ->whereDate('created_at', '<=', $filter['to_date'])
            ->select('driver_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('driver_id')
            ->with('driver')
            ->get();
    } 
    public function getOrderCompany($filter){
           return Order::
            whereDate('created_at', '>=', $filter['from_date'])
           ->whereDate('created_at', '<=', $filter['to_date'])
            ->select('company_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('company_id')
            ->with('company')
            ->get();
    }
    public function getOrderGovernorate($filter){
        $repo=Order::whereBetween('created_at', [$filter['from_date'], $filter['to_date']]);
       if(isset($filter['company_id'])){
        $repo->where('company_id',$filter['company_id']);
        }
   return $repo->select('governorate_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('governorate_id')
            ->with('governorate')
            ->get();
    }
}