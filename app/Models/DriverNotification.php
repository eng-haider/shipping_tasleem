<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverNotification extends Model
{
    use SoftDeletes, HasUuids;
    protected $guarded =[];
    
    public $columns = [
        'id', 'driver_id', 'notification_id', 'is_seen','updator_id', 'created_at', 'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public $relations = [];
    protected $hidden = ['deleted_at'];
    protected $casts = [
        'is_seen' => 'boolean'
    ];
    
    //Relations
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
    public function updator()
    {
        return $this->belongsTo(Driver::class, 'updator_id');
    }
}