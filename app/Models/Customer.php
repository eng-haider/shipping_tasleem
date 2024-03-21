<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Panoscape\History\HasHistories;
class Customer extends Model
{
    use SoftDeletes, HasUuids,HasHistories;
    protected $guarded =[];
    
    public $columns = [
        'id', 'name', 'phone', 'address', 'creator_id', 'updator_id', 'created_at', 'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public $relations = ['creator', 'updator', 'governorate'];
    protected $hidden = ['deleted_at'];
    protected $casts = [];
    

    public function getModelLabel()
    {
        return $this->display_name;
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
    //orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}