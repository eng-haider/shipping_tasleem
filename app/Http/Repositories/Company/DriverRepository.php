<?php
namespace App\Http\Repositories\Company;

use App\Helper\Helper;
use App\Models\Driver;
use PHPUnit\TextUI\Help;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Repositories\BaseRepository;

class DriverRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Driver());
    }
    public function getMyDriversList($take = 10)
    {
        $result = QueryBuilder::for($this->model)
                                ->where('company_id', auth('user')->user()->company->company_id)
                                ->where('is_active', 1)
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
        return $result->paginate($take);
    } 
    public function showMyDriver($id)
    {
        $result = QueryBuilder::for($this->model)
                                ->where('company_id', auth('user')->user()->company->company_id)
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
        return $result->findOrFail($id);
    } 

    public function show($id)
    {
        return QueryBuilder::for($this->model)
                            ->where('company_id', auth('user')->user()->company->company_id)
                            ->where('is_active', 1)
                            ->allowedIncludes($this->model->relations)
                            ->findOrFail($id);
    }
    public function create($data)
    {
        $checkDriver = $this->model->where('phone', $data['phone'])->get();
        foreach ($checkDriver as $driver) {
            if ($driver->company_id != auth('user')->user()->company->company_id && $driver->is_active == 1) {
                return Helper::responseError('This phone number is already taken with anther company, please inactive this driver and created retry', [], 400);
            }
        }
        $driverExists = $this->model->where('phone', $data['phone'])->where('company_id', auth('user')->user()->company->company_id)->first();
        if ($driverExists) {
            $driverExists->is_active = 1;
            $driverExists->save();
            return Helper::responseError('Create Driver Successfully', $driverExists, 200);
        }

        $response = $this->model->create($data);
        return Helper::responseError('Create Driver Successfully', $response, 200);
    }
}