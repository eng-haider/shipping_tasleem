<?php

namespace App\Http\Controllers;


use App\Helper\Helper;
use App\Models\Permission;
use App\Http\Requests\Index\Pagination;
use App\Http\Requests\Permission\Create;
use App\Http\Requests\Permission\Update;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Repositories\PermissionRepository;
use App\Http\Requests\Permission\AddPermissionsToUser;

class PermissionController extends Controller
{
    public function __construct(private PermissionRepository $PermissionRepo)
    {
        $this->middleware(['permissions:get-permission'])->only(['index']);
        $this->middleware(['permissions:store-permission'])->only(['store']);
        $this->middleware(['permissions:show-permission'])->only(['show']);
        $this->middleware(['permissions:update-permission'])->only(['update']);
        $this->middleware(['permissions:assign-permission-to-user'])->only(['assignPermissionsToUser']);
        $this->middleware(['permissions:delete-permission'])->only(['destroy']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->PermissionRepo->getList($request->take);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $permission = $request->validated();
        $permission['guard_name'] = 'user';
        $response = $this->PermissionRepo->create($permission);
        return Helper::responseSuccess('Create Permission Successfully', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Models\Permission  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = $this->PermissionRepo->show($id);
        return Helper::responseSuccess('Get Permission Successfully', $permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\Permission  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $permission = $request->validated();
        $response = $this->PermissionRepo->update($id, $permission);
        return Helper::responseSuccess('Update Permission Successfully', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\\$user  $user
     * @return \Illuminate\Http\Response
     */
    public function assignPermissionsToUser(AddPermissionsToUser $permissions, $userid)
    {
        $permissions->validated();
        $assign = $this->PermissionRepo->addPermissions($permissions['permissionsIds'], $userid);
        return Helper::responseSuccess('Assign PermissionTo User Successfully', $assign);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        // $response = $this->PermissionRepo->delete($permission);
        // return response()->json($response, Response::HTTP_OK);
    }
}
