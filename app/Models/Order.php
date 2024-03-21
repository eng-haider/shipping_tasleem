<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Panoscape\History\HasHistories;
class Order extends Model
{
    use SoftDeletes, HasHistories;
    protected $guarded =[];
    
    public $columns = [
        'id', 
        'uuid',
        'tr',
        'rate',
        'company_id', 
        'driver_id', 
        'status_id', 
        'governorate_id', 
        'user_updator_id', 
        'driver_updator_id', 
        'customer_id',
        'created_at', 
        'updated_at',
    ];

    public function getModelLabel()
    {
        return $this->display_name;
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            if(auth('driver')->user()){
                $model->driver_id = auth('driver')->user()->id;
            }
        });
        self::updating(function ($model) {
            if(auth('driver')->user()){
                $model->driver_updator_id = auth('driver')->user()->id;
            }else{
                $model->user_updator_id = auth('user')->user()->id;
            }
        });
    }
    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereBetween('updated_at', [$from, $to]);
    }
    public $relations = ['driverUpdator', 'userUpdator', 'company', 'driver', 'status', 'history', 'history.userUpdator', 'history.driver', 'customer', 'governorate'];
    protected $hidden = ['deleted_at'];
    protected $casts = [
        'rate' => 'double',
    ];
    //Relations
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id')->select(['id', 'name']);
    }
    public function driverUpdator()
    {
        return $this->belongsTo(Driver::class, 'driver_updator_id', 'id')->select(['id', 'name']);
    }
    public function userUpdator()
    {
        return $this->belongsTo(User::class, 'user_updator_id', 'id')->select(['id', 'name']);
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id')->select(['id', 'name']);
    }
    public function status()
    {
        return $this->belongsTo(Status::class)->select(['id', 'name', 'color', 'image']);
    }
    public function history()
    {
        return $this->hasMany(OrderHistory::class, 'order_id', 'uuid')->with('driver', 'status')->orderBy('created_at', 'desc');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class, 'governorate_id', 'id')->select(['id', 'name']);
    }

}