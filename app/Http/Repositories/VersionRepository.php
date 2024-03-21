<?php
namespace App\Http\Repositories;

use App\Models\Version;

class VersionRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Version());
    }
    public  function getVersion($value = '')
    {
        if($value){
            return $this->model->where('version', $value)->first();
        }
    }
}