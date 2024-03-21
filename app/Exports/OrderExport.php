<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Order;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private $take, private $startDate, private $endDate, private $columns, protected $model = new Order())
    {}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        $startDate = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);
        $data = QueryBuilder::for($this->model)
                ->allowedFilters($this->model->columns)
                ->allowedSorts($this->model->columns)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->with('status', 'company', 'governorate', 'driver', 'customer');
                if($this->take){
                    $data = $data->take($this->take)->skip(0);
                }
        $data = $data->get();
        return $data;
    }

    public function headings(): array
    {
        return $this->columns;
    }

    public function map($row): array
    {
        foreach($this->columns as $column){
            if($column == 'companyName'){
                $rows[] = ($row->company)?$row->company->name : '';
            }elseif($column == 'statusName'){
                $rows[] = ($row->status)?$row->status->name : '';
            }elseif($column == 'customerName'){
                $rows[] = ($row->customer)?$row->customer->name: '';
            }elseif($column == 'customerPhone'){
                $rows[] = ($row->customer)?$row->customer->phone : '';
            }elseif($column == 'customerAddress'){
                $rows[] = ($row->customer)?$row->customer->address : '';
            }elseif($column == 'driverName'){
                $rows[] = ($row->driver)?$row->driver->name : '';
            }elseif($column == 'driverPhone'){
                $rows[] = ($row->driver)?$row->driver->phone : '';
            }elseif($column == 'governorateName'){
                $rows[] = ($row->governorate)?$row->governorate->name : '';
            }else{
                $rows[] = $row->$column;
            }
        }
        return $rows;
    }
}
