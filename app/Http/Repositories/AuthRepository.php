<?php
namespace App\Http\Repositories;

use App\Models\Role;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\OtpSmsService;
use Illuminate\Support\Facades\Cache;
class AuthRepository extends BaseRepository{
    private $otpSmsService;
    public function __construct(OtpSmsService $otpSmsService)
    {
        $this->otpSmsService = $otpSmsService;
        parent::__construct(new User());
        
     
    }
    function SendOtp($credentials){
        $otp = rand(100000, 999999);
        $user = $this->model->where('phone', $credentials['phone'])->with('company')->first();
        if($user->company){
            return [[
                'status' => false,
                'message' => 'User not found.',
            ], 'code' => 404];
        }
        $this->otpSmsService->sendOtp($credentials['phone'],$otp);
        Cache::put('otp_driver' . $credentials['phone'], $otp, now()->addMinutes(1)); // Store OTP for 5 minutes, adjust as needed
        return [[
            'status' => true,
            'message' => 'Otp Send  successfully.',
        ], 'code' => 200];
    }
    function authenticate($credentials){
        config()->set('jwt.ttl', 60*60*365);
        $user = $this->model->where('phone', $credentials['phone'])->first();    
        $cached_otp = Cache::get('otp_driver' . $credentials['phone']);
        if (intval($cached_otp) !== intval($credentials['otp']))  {   
          return [[
            'status' => false,
            'message' => 'OTP validation failed.',
            ], 'code' => 404];      
        }
        if (!$token = JwtAuth::fromUser($user)) {
            return [[
                'status' => false,
                'message' => 'Login credentials are invalid.',
                ], 'code' => 401];
        }
        Cache::forget('otp_driver' . $credentials['phone']);
        return [[
            'token' => $token,
            'status' => true,
            'message' => 'Logged in successfully.',
            'data' => $user,
        ], 'code' => 200];
    }
    public function getUserPermissions($user){
        $user = (auth('user')->user())? auth('user')->user():$user;
        $user = $this->model->where('id', $user->id)->with('roles')->first();
        if(!count($user->roles)){
            return ['roles' => [], 'permissions' => []];
        }
        $rolesId = [];
        $rolesName = [];
        foreach ($user->roles as $role) {
            $rolesId[] =  $role->id;
            $rolesNames[] =  $role->name;
        }
        $roles =  Role::with('permissions')->whereIn('id', $rolesId)->get();
        $userPermissions = $user->getPermissionNames()->toArray();
        foreach($roles as $role){
            foreach($role->permissions->pluck('name') as $rolePermission){
                if(!in_array($rolePermission, $userPermissions)){
                    $userPermissions[] = $rolePermission;
                }
            }
        }
        return ['roles' => $rolesNames, 'permissions' => $userPermissions];
    }
    public function getNamesPermissions($roleId){
        $role = Role::findOrFail($roleId);
        return $role->permissions->pluck('name');
    }
    public function getUserAuth(){
        $user = $this->model->where('id', auth('user')->user()->id)->first();
        return[
            'userAuth' => $user,
            'roles' => $this->getUserPermissions($user)['roles'],
            'permissions' => $this->getUserPermissions($user)['permissions'],
        ];
    }  
}