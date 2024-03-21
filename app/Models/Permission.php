<?php

namespace App\Models;

use DateTimeInterface;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Panoscape\History\HasHistories;
class Permission extends Model
{
    use SoftDeletes, HasRoles, HasHistories;
    protected $guarded =[];
    public $columns = [
        'id', 'name', 'guard_name', 'created_at', 'updated_at'
    ];

    public function getModelLabel()
    {
        return $this->display_name;
    }

    
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public $relations = [];
    protected $hidden = ['pivot'];
    protected $casts = [];
}
