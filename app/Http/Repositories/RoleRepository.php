<?php
namespace App\Http\Repositories;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Http\Repositories\BaseRepository;

class RoleRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Role());
    }

    public function create($data){
       return $this->model->create($data);
    }
    public function addRole($roles, $userId){
        $role['role_id'] = $roles[0];
        $role['model_type'] = 'App\Models\User';
        $role['model_id'] = $userId;
        DB::table('model_has_roles')->where('model_id', $userId)->where('model_type','App\Models\User')->delete();
        DB::table('model_has_roles')->insert($role);
        return $role;
    }
    public function assignPermission($permissionIds, $roleId){
        $role = Role::findOrFail($roleId);
        $role->syncPermissions($permissionIds);
        return $role;
    }
    public function getNamesPermissions($roleId){
        $role = Role::findOrFail($roleId);
        return $role->permissions->pluck('id','name');
    }
    public function deletePremissions($permissionsIds, $roleId){
        return DB::table('model_has_permissions')->where('model_type', 'App\Models\Role')->where('model_id', $roleId)->whereIn('permission_id', $permissionsIds)->delete();
    }
    public function getRoleByName($name){
        return $this->model->where('name', $name)->first();
    }
}
