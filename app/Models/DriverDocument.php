<?php

namespace App\Models;

use DateTimeInterface;
use Panoscape\History\HasHistories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DriverDocument extends Model
{
    use SoftDeletes, HasUuids, HasHistories;
    protected $guarded =[];
    
    public $columns = [
        'id', 'file', 'driver_id', 'identity_type', 'status', 'created_at', 'updated_at'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getModelLabel()
    {
        return $this->display_name;
    }

    public function getImageAttribute($value)
    {
        return $value ? asset($value) : null;
    }
    
    public $relations = ['driver','Document'];
    protected $hidden = ['deleted_at'];
    protected $casts = [
        'status' => 'integer',
        'identity_type' => 'integer'
    ];
    protected $appends = ['file_url'];
    public function getFileUrlAttribute()
    {
        return $this->file ? asset($this->file) : null;
    }
    
    public function Document()
    {
        return $this->belongsTo(Document::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

 
    
}