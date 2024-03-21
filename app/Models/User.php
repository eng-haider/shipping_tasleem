<?php
namespace App\Models;
use DateTimeInterface;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Panoscape\History\HasHistories;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles, HasHistories;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'image',
        'is_active',
    ];
    public function getModelLabel()
    {
        return $this->display_name;
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public $columns = [
        'name',
        'phone',
        'is_active',
    ];
    public $relations = ['userCompany', 'userCompany.company', 'userCompany.governorate', 'roles'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
        'remember_token',
        'pivot'
    ];
    protected function getDefaultGuardName(): string { return 'user'; }
    // protected $appends = ['roles_names'];
    // public function getRolesNamesAttribute() {
    //     return $this->roles->pluck('name');
    // }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //getters
    public function getImageAttribute($value) {
        return $value ? asset($value) : null;
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
    public function company()
    {
        return $this->hasOne(UserCompany::class, 'user_id', 'id')->where('is_active', 1);
    }
    public function userCompany()
    {
        return $this->hasOne(UserCompany::class, 'user_id', 'id')->where('is_active', 1);
    }
}