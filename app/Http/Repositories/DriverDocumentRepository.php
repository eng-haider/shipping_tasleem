<?php
namespace App\Http\Repositories;

use App\Models\DriverDocument;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Enums\DriverDocumentIdentityTypeEnum;
use ReflectionClass;
use App\Models\DriverVehicle;
use App\Models\Document;
class DriverDocumentRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new DriverDocument());
    }
    //Get all resources
    public function getMyDriverDocumentsList($take = 10)
    {
        $result = QueryBuilder::for($this->model)
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns)
                                ->where('file', 'LIKE', '%driver-document-images%')
                                ;
        return $result->paginate($take);
    } 






   

}