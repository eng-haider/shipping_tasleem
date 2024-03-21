<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = config('rolesAndPermissions');
        $adminPermissinIds = [];
        //add super admin role
        $superAdminRole = Role::where('name', 'super-admin')->where('guard_name','user')->first();
        if(!$superAdminRole){
            $superAdminRole = Role::create([
                'name' => 'super-admin',
                'guard_name' => 'user'
            ]);
        }
        //add super admin permissions
        foreach($roles['super-admin'] as $permision){
            $permission = Permission::where('name', $permision)->where('guard_name','user')->first();
            if(!$permission){
                $permission = Permission::create([
                    'name' => $permision,
                    'guard_name' => 'user'
                ]);
            }
            $adminPermissinIds[] = $permission->id;
        }
        //assign super admin permissions to super admin role
        $this->assignPermissionToRole($adminPermissinIds, $superAdminRole->id);

        //add company admin role 
        $companyPermissinIds = [];
        $companyAdminRole = Role::where('name', 'company-admin')->where('guard_name','user')->first();
        if(!$companyAdminRole){
            $companyAdminRole = Role::create([
                'name' => 'company-admin',
                'guard_name' => 'user'
            ]);
        }
        //add company admin permissions
        foreach($roles['company-admin'] as $permision){
            $permission = Permission::where('name', $permision)->where('guard_name','user')->first();
            if(!$permission){
                $permission = Permission::create([
                    'name' => $permision,
                    'guard_name' => 'user'
                ]);
            }
            $companyPermissinIds[] = $permission->id;
        }
        //assign company admin permissions to company admin role
        $this->assignPermissionToRole($companyPermissinIds, $companyAdminRole->id);
    }
    public function assignPermissionToRole($permissinIds, $roleId){
        foreach($permissinIds as $permissionId){
            $checkExists = DB::table('model_has_permissions')->where('permission_id', $permissionId)->where('model_id', $roleId)->where('model_type','App\Models\Role')->first();
            if(!$checkExists){
                DB::table('model_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'model_id' => $roleId,
                    'model_type' => 'App\Models\Role',
                ]);
            } 
        }
    }
}
