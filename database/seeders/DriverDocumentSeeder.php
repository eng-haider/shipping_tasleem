<?php
namespace Database\Seeders;
use App\Models\DriverDocument;
use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ReflectionClass;
use App\Models\Driver;
use App\Models\DriverVehicle;
use Illuminate\Support\Facades\DB;
class DriverDocumentSeeder extends Seeder
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
            
          $drivers = Driver::all();
           foreach ($drivers as $driver) {
            $documents = Document::all();
            foreach ($documents as $document) {
                for($i=1;$i<=$document->image_number;$i++){
                    // if(DriverDocument::where('driver_id',$driver->id)->where('document_id',$document->id)->count()==0)
                    // {
                        $driver->documents()->create([
                            'document_id' => $document->id,
                            'driver_id'=>$driver->id,
                            'file'=>$i==1?$document->front_image:$document->back_image,
                            'title'=>$i==1?'Front side image':'Back side image'
                        ]);
                    // }
                   
                }
              
            }
              
           }
             
    
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
