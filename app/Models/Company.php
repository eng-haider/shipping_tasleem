<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Panoscape\History\HasHistories;
class Company extends Model
{
    use SoftDeletes, HasUuids,HasHistories;
    protected $guarded =[];
    
    public $columns = [
        'id', 'name', 'company_cdc_id', 'creator_id', 'updator_id', 'created_at', 'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->creator_id = auth('user')->user()?auth('user')->user()->id:null;
        });
        self::updating(function ($model) {
            $model->updator_id = auth('user')->user()?auth('user')->user()->id:null;
        });
    }
    public $relations = ['creator', 'updator', 'governorates'];
    protected $hidden = ['deleted_at'];
    protected $casts = [];
    

    public function getModelLabel()
    {
        return $this->display_name;
    }

    
    //getter
    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
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
    public function governorates()
    {
        return $this->belongsToMany(Governorate::class, 'company_governorates', 'company_id', 'governorate_id');
    }
}