<?php
namespace App\Http\Repositories;

use App\Models\Status;

class StatusRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Status());
    }
    public function getReceivedStatus(){
        return $this->model->where('name', 'Received')->firstOrFail();
    }
}