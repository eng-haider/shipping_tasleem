<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helper\Helper;
use App\Http\Requests\User\Create;
use App\Http\Requests\User\Update;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Index\Pagination;
use App\Http\Requests\Role\Permissions;
use App\Http\Repositories\RoleRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\User\CreateUserCompany;
use App\Http\Requests\User\Params;

class UserController extends Controller
{
    public function __construct(private UserRepository $UserRepo)
    {
        $this->middleware(['permissions:get-user'])->only(['index']);
        $this->middleware(['permissions:store-user'])->only(['store', 'userCompany']);
        $this->middleware(['permissions:show-user'])->only(['show']);
        $this->middleware(['permissions:update-user'])->only(['update']);
        $this->middleware(['permissions:delete-user'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Params $request)
    {
        $request->validated();
        return $this->UserRepo->getUserList($request->take, $request->user_type, $request->company_id);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $user = $request->validated();
        DB::beginTransaction();
        try {
            $RoleRepo = new RoleRepository();
            // $role = $RoleRepo->getRoleByName('super-admin');
            // if(!$role)
            //     return Helper::responseError('Role not found', [], 400);

            // $roleId = $role->id;
            $roleId = $user['role_id'];
            unset($user['role_id']);
            $response = $this->UserRepo->create($user);
            $RoleRepo->addRole([$roleId], $response['id']);
            DB::commit();
            return Helper::responseSuccess('Create user successfully', $response);
        } catch (\Exception $e) {
            DB::rollback();
            return Helper::responseError($e->getMessage(), [], 400);
        }
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userCompany(CreateUserCompany $request)
    {
        $user = $request->validated();
        DB::beginTransaction();
        try {
            $RoleRepo = new RoleRepository();
            $role = $RoleRepo->getRoleByName('company-admin');
            if(!$role)
                return Helper::responseError('Role not found', [], 400);

            $roleId = $role->id;
            $response = $this->UserRepo->createUserCompany($user);
            $RoleRepo->addRole([$roleId], $response['id']);
            DB::commit();
            return Helper::responseSuccess('Create user successfully', $response);
        } catch (\Exception $e) {
            DB::rollback();
            return Helper::responseError($e->getMessage(), [], 400);
        }
    }

    /**
     * Display the User resource.
     *
     * @param  \App\Models\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->UserRepo->show($id);
        return Helper::responseSuccess('Get user successfully', $response);
    }

    /**
     * Display the User resource.
     *
     * @param  \App\Models\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function getUserPermissions($id)
    {
        return $this->UserRepo->getUserPermissions($id);
    }

    /**
     * Update the User resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $user = $request->validated();
        $response = $this->UserRepo->update($id, $user);
        return Helper::responseSuccess('Update user successfully', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->id == 1){
            return Helper::responseError('You can`t delete admin user', [], 400);
        }
        $response = $this->UserRepo->delete($user);
        return Helper::responseSuccess('Delete user successfully', $response);
    }

    /**
     * Remove Premissions From User.
     *
     * @param  \App\Models\Models\Role  $roleId
     * @return \Illuminate\Http\Response
     */
    public function destroyPremissionsFromUser(Permissions $permissions, $userId)
    {
        $permissions = $permissions->validated();
        $this->UserRepo->deletePremissionsFromUser($permissions['permissionIds'], $userId);
        return Helper::responseSuccess('Delete all permissions from this user', []);
    }
    
    //active and deactive user
    public function activeDeactiveUser(User $user)
    {
        if($user->is_active == 1){
            $user->is_active = 0;
            $user->save();
            return Helper::responseSuccess('User Deactivated successfully', $user);
        }else{
            $user->is_active = 1;
            $user->save();
            return Helper::responseSuccess('User Activated successfully', $user);
        }
        
    }
}
