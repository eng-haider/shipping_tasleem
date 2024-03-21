<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
// use Panoscape\History\HasHistories;
class UserCompany extends Model
{
    use SoftDeletes, HasUuids;
    protected $guarded =[];
    
    public $columns = [
        'id', 'governorate_id', 'company_id', 'user_id', 'creator_id', 'updator_id', 'created_at', 'updated_at'
    ];

    // public function getModelLabel()
    // {
    //     return $this->display_name;
    // }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->creator_id = (auth('user')->user())? auth('user')->user()->id: null;
        });
        self::updating(function ($model) {
            $model->updator_id = (auth('user')->user())? auth('user')->user()->id: null;
        });
    }
    public $relations = ['creator', 'updator'];
    protected $hidden = ['deleted_at'];
    protected $casts = [];
    
    //Relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id')->select(['id', 'name']);
    }
    public function updator()
    {
        return $this->belongsTo(User::class, 'updator_id', 'id')->select(['id', 'name']);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}