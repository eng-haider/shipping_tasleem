<?php
namespace App\Http\Repositories;

use App\Models\Driver;

class DriverRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Driver());
    }
}