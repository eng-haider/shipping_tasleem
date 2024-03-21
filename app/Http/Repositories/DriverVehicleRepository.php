<?php
namespace App\Http\Repositories;

use App\Models\DriverVehicle;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use App\Enums\DriverVehicleIdentityTypeEnum;
use ReflectionClass;
class DriverVehicleRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new DriverVehicle());
    }
 





 

}