<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Panoscape\History\HasHistories;

class Governorate extends Model
{
    use SoftDeletes, HasUuids, HasHistories;
    protected $guarded =[];
    
    public $columns = [
        'id', 'name', 'creator_id', 'updator_id', 'created_at', 'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public $relations = ['creator', 'updator'];
    protected $hidden = ['deleted_at', 'pivot'];
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
    public function companies()
    {
        return $this->hasMany(Company::class, 'governorate_id', 'id');
    }
    public function company()
    {
        return $this->belongsToMany(Company::class, 'company_governorates', 'governorate_id', 'company_id');
    }
}