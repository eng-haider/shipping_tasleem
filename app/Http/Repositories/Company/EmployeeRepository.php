<?php
namespace App\Http\Repositories\Company;

use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository {
    
    public function __construct(private User $model)
    {
        $this->model = $model;
    }
    public function getMyEmployees($take = 10): LengthAwarePaginator
    {
        $result = QueryBuilder::for($this->model)
                                ->whereHas('company', function ($query) {
                                    $query->where('company_id', auth('user')->user()->company->company_id);
                                })
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
        return $result->paginate($take);
    } 
    public function show($id): Model
    {
        $result = QueryBuilder::for($this->model)
                                ->whereHas('company', function ($query) {
                                    $query->where('company_id', auth('user')->user()->company->company_id);
                                })
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
        return $result->findOrFail($id);
    } 
}
