<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ReflectionClass;
use App\Models\Driver;
use App\Models\DriverVehicle;
use Illuminate\Support\Facades\DB;
class DriverVehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {

             foreach(Driver::get() as $Driver){
               // Create a new instance of DriverVehicle model
               if(count(DriverVehicle::where('driver_id','=',$Driver->id)->get())==0){
                $driverVehicle = new DriverVehicle();
                $driverVehicle->driver_id =$Driver->id;
                $driverVehicle->save();
               }
                
              
             }
             
    
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
