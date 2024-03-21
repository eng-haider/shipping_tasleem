<?php

namespace App\Models;

use DateTimeInterface;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Panoscape\History\HasHistories;
use App\Models\DriverDocument;
use App\Models\DriverVehicle;
use App\Models\Document;


class Driver extends Authenticatable implements JWTSubject
{
    use SoftDeletes, HasUuids,HasHistories;
    protected $guarded =[];
    
    public $columns = [
        'id', 'name', 'phone', 'personal_code', 'is_block', 'is_verified', 'image', 'company_id', 'governorate_id', 'creator_id', 'updator_id', 'created_at', 'updated_at'
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
            $model->creator_id = auth('user')->user()?auth('user')->user()->id:null;
        });

        static::created(function ($driver) {
            $documents = Document::all();

            foreach ($documents as $document) {
                for($i=1;$i<=$document->image_number;$i++){
                    $driver->documents()->create([
                        'document_id' => $document->id,
                        'file'=>$i==1?$document->front_image:$document->back_image,
                        'title'=>$i==1?'Front side image':'Back side image'
                    ]);
                }
              
            }
        });


        static::created(function ($driver) {
            $driver->vehicle()->create([
                'driver_id' => $driver->id,
            ]);
    
    });



    }
    public $relations = ['creator', 'updator', 'company', 'governorate', 'orders', 'documents', 'vehicle','documents.document'];
    protected $hidden = ['deleted_at', 'password'];
    protected $casts = [
        'is_active' => 'boolean'
    ];
    protected $appends = ['image_path'];
    //getters image
    public function getImagePathAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
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
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
    public function documents()
    {
        return $this->hasMany(DriverDocument::class);
    }
    public function vehicle()
    {
        return $this->hasOne(DriverVehicle::class);
    }
}