<?php

namespace App\Models;

use DateTimeInterface;
use Panoscape\History\HasOperations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverVehicle extends Model
{
    use SoftDeletes, HasUuids,HasOperations;
    protected $guarded =[];
    
    public $columns = [
        'id', 
        'name', 
        'vehicle_no', 
        'vehicle_type', 
        'color', 
        'image', 
        'driver_id', 
        'creator_id', 
        'updator_id', 
        'created_at', 
        'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public $relations = ['creator', 'updator', 'governorate'];
    protected $hidden = ['deleted_at'];
    protected $casts = [
        'vehicle_type' => 'integer',
    ];
    //getters
    public function getImageAttribute($value)
    {
        return $value ? asset($value) : null;
    }
    //Relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id')->select(['id', 'name']);
    }
    public function updator()
    {
        return $this->belongsTo(User::class, 'updator_id', 'id')->select(['id', 'name']);
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id')->select(['id', 'name']);
    }

}