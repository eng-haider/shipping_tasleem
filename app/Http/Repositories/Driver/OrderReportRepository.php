<?php
namespace App\Http\Repositories\Driver;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
class OrderReportRepository{
    
    public function getOrderStatus($from_date, $to_date){
        // return [$from_date, $to_date];
        // $report = Order::where('driver_id', auth('driver')->user()->id);
        //     if($from_date && $to_date){
        //         $report = $report->whereBetween('updated_at', [$from_date, $to_date]);
        //     }
        //     $report = $report->wheredoesnthave('status', function($query){
        //         $query->where('name', 'Received');
        //     })
        //     ->select('status_id', DB::raw('COUNT(*) as order_count'))
        //     ->groupBy('status_id')
        //     ->with('status')
        //     ->get();
        // $recieved = Order::where('driver_id', auth('driver')->user()->id)
        //     ->whereHas('status', function($query){
        //         $query->where('name', 'Received');
        //     })
        //     ->select('status_id', DB::raw('COUNT(*) as order_count'))
        //     ->groupBy('status_id')
        //     ->with('status')
        //     ->get();
        //     if($recieved->count() > 0 && $report->count() > 0){
        //         $report = $report->concat($recieved);
        //         return $report;
        //     }elseif($recieved->count() > 0 && $report->count() == 0){
        //         return $recieved;
        //     }elseif($recieved->count() == 0 && $report->count() > 0){
        //         return $report;
        //     }else{
        //         return [];
        //     }

        $allStatuses = Status::select('id', 'name','color','image')->get();

        $report = Order::where('driver_id', auth('driver')->user()->id);
        
        if ($from_date && $to_date) {
            $report->whereDate('updated_at', '>=', $from_date)->whereDate('updated_at', '<=', $to_date);;
        }

        
        $report = $report->select('status_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('status_id')
            ->get();
        
        // Merge the orders count with all statuses
        return  $report= $allStatuses->map(function ($status) use ($report) {
            $orderCount = $report->firstWhere('status_id', $status->id);
            return [
                'status_id' => $status->id,
                'order_count' => $orderCount ? $orderCount->order_count : 0,
                'status' => $status,
            ];
        });
    
   

            // $recieved = Order::where('driver_id', auth('driver')->user()->id)
            // ->whereHas('status', function($query){
            //     $query->where('name', 'Received');
            // })
            // ->select('status_id', DB::raw('COUNT(*) as order_count'))
            // ->groupBy('status_id')
            // ->with('status')
            // ->get();

        
      
        
    }
}