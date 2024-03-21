<?php
namespace App\Http\Repositories;

use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class UserRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new User());
    }

    //Get all resources
    public function getUserList($take = 10, $userType = 'all', $companyId = null)
    {
        $result = QueryBuilder::for($this->model)
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
                                if($userType == 'company-admin'){
                                    $result->whereHas('company');
                                    if($companyId){
                                        $result->whereHas('userCompany', function($query) use ($companyId){
                                            $query->where('company_id', $companyId);
                                        });
                                    }
                                }
                                if($userType == 'super-admin'){
                                    $result->whereDoesntHave('company');
                                }
        return $result->paginate($take);
    }
    public function deletePremissionsFromUser($permissionsIds, $userId){
        return DB::table('model_has_permissions')
                    ->where('model_type', 'App\Models\User')
                    ->where('model_id', $userId)->whereIn('permission_id', $permissionsIds)
                    ->delete();
    }
    public function getUserPermissions($userId){
        return DB::table('model_has_permissions')
                    ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
                    ->where('model_type', 'App\Models\User')
                    ->where('model_id', $userId)
                    ->select('permissions.id', 'permissions.name' )
                    ->get();
    }
    public function createUserCompany($data){
        $user = $this->model->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
        ]);
        UserCompany::create([
            'governorate_id' => $data['governorate_id'],
            'company_id' => $data['company_id'],
            'user_id' => $user['id'],

        ]);
        return $user;
    }
}
