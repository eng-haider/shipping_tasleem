<?php
namespace App\Http\Repositories\Driver;

use App\Models\DriverDocument;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Repositories\BaseRepository;
use App\Models\Document;

class DriverDocumentRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new DriverDocument());
    }


    public function getMyDriverDocumentsList($take = 10)
    {
        $result = QueryBuilder::for($this->model)
                                ->where('driver_id', auth('driver')->user()->id)
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
        return $result->paginate($take);
    } 
  public function getDocument($take = 10)
    {
        $result = QueryBuilder::for(Document::class)
        ->with(['driverDocument' => function ($query) {
            $query->where('driver_id', auth('driver')->user()->id);
        }])
        ->allowedFilters($this->model->columns)
        ->allowedSorts($this->model->columns)
      ;
    
        return $result->paginate($take);
    } 
    

    public function showDocument($driverId, $document)
    {
        return QueryBuilder::for($this->model)
                            ->allowedIncludes($this->model->relations)
                            ->where('driver_id', $driverId)
                            ->where('document_id', $document)
                            ->get();
    }
}