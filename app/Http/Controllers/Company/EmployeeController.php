<?php

namespace App\Http\Controllers\Company;

use App\Helper\Helper;
use App\Http\Requests\User\Update;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\RoleRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\User\UpdateEmployee;
use App\Http\Repositories\Company\EmployeeRepository;
use App\Http\Requests\User\CreateEmployeeFromCompany;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeRepository $EmployeeRepo, private UserRepository $UserRepo)
    {
        $this->middleware(['permissions:company-get-user'])->only(['index']);
        $this->middleware(['permissions:company-store-user'])->only(['store']);
        $this->middleware(['permissions:company-show-user'])->only(['show']);
        $this->middleware(['permissions:company-update-user'])->only(['update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->EmployeeRepo->getMyEmployees($request->take);
        
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEmployeeFromCompany $request)
    {
        $user = $request->validated();
        DB::beginTransaction();
        try {
            $user['company_id'] = auth('user')->user()->company->company_id;
            $RoleRepo = new RoleRepository();
            $role = $RoleRepo->getRoleByName('company-admin');
            if(!$role)
                return Helper::responseError('Role not found', [], 400);

            $roleId = $role->id;
            $response = $this->UserRepo->createUserCompany($user);
            $RoleRepo->addRole([$roleId], $response['id']);
            DB::commit();
            return Helper::responseSuccess('Create employee successfully', $response);
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
        return Helper::responseError('Get Employee Successfully', $this->EmployeeRepo->show($id), 200);
    }

    /**
     * Update the User resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployee $request, $id)
    {
        $user = $request->validated();
        $this->EmployeeRepo->show($id);
        $response = $this->UserRepo->update($id, $user);
        return Helper::responseSuccess('Update employee successfully', $response);
    }
    public function destroy($id)
    {
        $model = $this->EmployeeRepo->show($id);
        $response = $this->UserRepo->delete($model);
        return Helper::responseSuccess('Delete employee successfully', $response);
    }
}
