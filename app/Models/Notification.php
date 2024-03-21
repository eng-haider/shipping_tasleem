<?php

namespace App\Models;

use DateTimeInterface;
use Panoscape\History\HasOperations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use SoftDeletes, HasUuids;
    protected $guarded =[];
    
    public $columns = [
        'id', 'title', 'description', 'creator_id', 'updator_id', 'created_at', 'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public $relations = ['drivers', 'details'];
    protected $hidden = ['deleted_at'];
    protected $casts = [];
    //Relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function updator()
    {
        return $this->belongsTo(User::class, 'updator_id');
    }
    public function drivers()
    {
        return $this->hasMany(DriverNotification::class, 'notification_id', 'id')->with('driver');
    }
    public function details(){
        return $this->belongsTo(DriverNotification::class, 'id', 'notification_id')->where('driver_id', auth('driver')->user()->id)->with('driver');
    }
}