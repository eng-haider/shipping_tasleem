<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Version extends Model
{
    use  HasUuids;
    protected $guarded = [];
    
    public $columns = [
        'id', 
        'version', 
        'android_url', 
        'android_public', 
        'android_active', 
        'android_cache', 
        'ios_url', 
        'ios_public', 
        'ios_active', 
        'ios_cache',
        'creator_id', 
        'updator_id', 
        'created_at', 
        'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public $relations = [];
    protected $hidden = ['deleted_at'];
    protected $casts = [
        'android_public' => 'boolean',
        'android_active' => 'boolean',
        'android_cache' => 'boolean',
        'ios_public' => 'boolean',
        'ios_active' => 'boolean',
        'ios_cache' => 'boolean',
    ];
}