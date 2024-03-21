<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Status extends Model
{
    use SoftDeletes, HasUuids;
    protected $guarded =[];
    
    public $columns = [
        'id', 'name', 'color', 'creator_id', 'updator_id', 'created_at', 'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public $relations = ['creator', 'updator'];
    protected $hidden = ['deleted_at'];
    protected $casts = [];
    //getter
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
}