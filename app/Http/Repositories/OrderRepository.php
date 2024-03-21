<?php
namespace App\Http\Repositories;

use App\Models\Order;
use App\Exports\OrderExport;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\QueryBuilder;

class OrderRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Order());
    }
    public function getOrderList($take = 10, $from = null, $to = null)
    {
        $result = QueryBuilder::for($this->model);
                                if($from && $to){
                                    // $result->betweenDates($from, $to);
                                    $result->whereDate('updated_at', '>=', $from)->whereDate('updated_at', '<=', $to);
                                }
                                $result->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
        return $result->paginate($take);
    } 
    public function changeStatus($uuid, $status){
        DB::beginTransaction();
        try{
            $order = $this->model->where('uuid', $uuid)->firstOrFail();
            $order->update($status);
            $status['order_id'] = $uuid;
            OrderHistory::create($status);
            DB::commit();
            return [[
                'status' => true,
                'message' => 'Status Updated Successfully',
                'data' => $order
            ], 200];
        }catch(\Exception $e){
            DB::rollBack();
            return [[
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400];
        }
    }
    public function checkOrderStatusExists($uuid, $statusId)
    {
        return $this->model->where('uuid', $uuid)->where('status_id', $statusId)->exists();
    }
    public function exportOrders($take = null, $from = null, $to = null, $columns = null)
    {
        return Excel::download(new OrderExport($take, $from, $to, $columns),'طلبات.'.date('d.m.Y').'.'.rand().'.xlsx');
    }
}