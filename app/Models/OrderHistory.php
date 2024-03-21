<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderHistory extends Model
{
    use SoftDeletes, HasUuids;
    protected $guarded =[];
    
    public $columns = [
        'id', 
        'long', 
        'lat',
        'fingerprint', 
        'order_id', 
        'driver_id', 
        'status_id', 
        'updator_id', 
        'created_at', 
        'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->driver_id = (auth('driver')->user())?auth('driver')->user()->id:null;
        });
    }
    public $relations = ['creator', 'updator', 'order', 'driver', 'status', 'userUpdator'];
    protected $hidden = ['deleted_at'];
    protected $casts = [];
    //getter
    public function getImageAttribute($value)
    {
        return $value ? asset('orders-history-images/' . $value) : null;
    }
    //getter
    public function getFingerprintImageAttribute($value)
    {
        return $value ? asset('orders-history-fingerprint/' . $value) : null;
    }
    //Relations
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id')->select(['id', 'name']);
    }
    public function updator()
    {
        return $this->belongsTo(Driver::class, 'updator_id', 'id')->select(['id', 'name']);
    }
    public function status()
    {
        return $this->belongsTo(Status::class)->select(['id', 'name', 'color', 'image']);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function userUpdator()
    {
        return $this->belongsTo(User::class, 'user_updator_id', 'id')->select(['id', 'name']);
    }
}