<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Helper\Helper;
use App\Http\Requests\Role\Assign;
use App\Http\Requests\Role\Create;
use App\Http\Requests\Role\Update;
use App\Http\Requests\Index\Pagination;
use App\Http\Requests\Role\Permissions;
use App\Http\Repositories\RoleRepository;
use App\Http\Requests\Role\AddRoleToUser;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
   
    public function __construct(private RoleRepository $RoleRepo)
    {
        $this->middleware(['permissions:get-role'])->only(['index']);
        $this->middleware(['permissions:store-role'])->only(['store']);
        $this->middleware(['permissions:show-role'])->only(['show']);
        $this->middleware(['permissions:update-role'])->only(['update']);
        $this->middleware(['permissions:assign-permission-to-role'])->only(['assignPermissionToRole']);
        $this->middleware(['permissions:assign-role-to-user'])->only(['assignRoleToUser']);
        $this->middleware(['permissions:delete-role'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->RoleRepo->getList($request->take);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $role = $request->validated();
        $role['guard_name'] = 'api';
        $newRole = $this->RoleRepo->create($role);
        if($request->has('permissionIds')){
            $this->assignRoleToPermissions($request->permissionIds, $newRole['id']);
        }
        return Helper::responseSuccess('Create Role Successfully', $newRole);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->RoleRepo->show($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function assignPermissionToRole(Assign $assign, $id)
    {
        $assign->validated();
        $assign = $this->RoleRepo->assignPermission($assign->permissionIds, $id);
        return Helper::responseSuccess('Assign Permission To Role Successfully', $assign);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\\$user  $user
     * @return \Illuminate\Http\Response
     */
    public function assignRoleToUser(AddRoleToUser $roles, $userid)
    {
        $roles->validated();
        $assign =  $this->RoleRepo->addRole($roles['roleIds'], $userid);
        return Helper::responseSuccess('Assign Role To User Successfully', $assign);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\\$user  $user
     * @return \Illuminate\Http\Response
     */
    public function getRolePermissionsName($roleIds)
    {
        $rolePermissions = $this->RoleRepo->getNamesPermissions($roleIds);
        return Helper::responseSuccess('Get Role Permissions Name Successfully', $rolePermissions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\Role  $Role
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $role = $request->validated();
        $response = $this->RoleRepo->update($id, $role);
        return Helper::responseSuccess('Update Role Successfully', $response);
    }

    /**
     * Remove Premissions From Role.
     *
     * @param  \App\Models\Models\Role  $roleId
     * @return \Illuminate\Http\Response
     */
    public function destroyPremissionsFromRole(Permissions $permissions, $roleId)
    {
        $permissions = $permissions->validated();
        $this->RoleRepo->deletePremissions($permissions['permissionIds'], $roleId);
        return response()->json([
            'status' => true,
            'message' => 'delete all permissions from this role'
        ], Response::HTTP_OK);
        return Helper::responseSuccess('Delete Permission From Role Successfully', []);
    }
}