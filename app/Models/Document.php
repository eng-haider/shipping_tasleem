<?php

namespace App\Models;

use DateTimeInterface;
use Panoscape\History\HasHistories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Driver;


class Document extends Model
{
    use SoftDeletes, HasUuids,HasHistories;
    protected $guarded =[];
    
    public $columns = [
        'id', 'name', 'created_at', 'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getImageAttribute($value)
    {
        return $value ? asset($value) : null;
    }
    public $relations = [];
    protected $hidden = ['deleted_at'];
    protected $casts = [];
    

    public function getModelLabel()
    {
        return $this->display_name;
    }

    public function DriverDocument()
    {
        return $this->hasMany(DriverDocument::class);
    }


       // Define a method to create driver documents for all drivers
       public function createDriverDocumentsForAllDrivers($id)
       {
           $drivers = Driver::all();
   
          $document = Document::find($id);

           foreach ($drivers as $driver) {
           
            // foreach ($documents as $document) {
                for($i=1;$i<=$document->image_number;$i++){
                    $driver->documents()->create([
                        'document_id' => $document->id,
                        'driver_id'=>$driver->id,
                        'file'=>$i==1?$document->front_image:$document->back_image,
                        'title'=>$i==1?'Front side image':'Back side image'
                    ]);
                }
              
            // }
              
           }
       }


}