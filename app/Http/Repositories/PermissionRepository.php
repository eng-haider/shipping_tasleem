<?php
namespace App\Http\Repositories;

use App\Models\User;
use App\Models\Permission;

class PermissionRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Permission());
    }
    public function addPermissions($permissionIds, $userId)
    {
        $user = User::findOrFail($userId);
        foreach($permissionIds as $permissionId){
            $user->givePermissionTo($permissionId);
        }
        return $user->load('permissions');
    }
}
