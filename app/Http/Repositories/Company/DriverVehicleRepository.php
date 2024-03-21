<?php
namespace App\Http\Repositories\Company;

use App\Models\DriverVehicle;
use App\Http\Repositories\BaseRepository;

class DriverVehicleRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new DriverVehicle());
    }
}