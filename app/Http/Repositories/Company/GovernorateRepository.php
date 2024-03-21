<?php
namespace App\Http\Repositories\Company;

use App\Models\Governorate;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Repositories\BaseRepository;

class GovernorateRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Governorate());
    }
    public function getMyCompanyGovernorateList($take = 10)
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
}