<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Company;

use App\Http\Requests\Company\Create;
use App\Http\Requests\Company\Update;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\CompanyRepository;

class CompanyController extends Controller
{
    public function __construct(private CompanyRepository $CompanyRepo)
    {
        $this->middleware(['permissions:get-company'])->only(['index']);
        $this->middleware(['permissions:store-company'])->only(['store']);
        $this->middleware(['permissions:show-company'])->only(['show']);
        $this->middleware(['permissions:update-company'])->only(['update']);
        $this->middleware(['permissions:delete-company'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->CompanyRepo->getList($request->take);
    }

    /**
     * Get list destroyed company.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDestroyedCompanyList(Pagination $request)
    {
        $request->validated();
        return $this->CompanyRepo->getDestroyedList($request->take);
    }

    /**
     * Store a newly created Company in storage.
     *
     * @param  \Illuminate\Http\Company\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $company = $request->validated();
        $check = Helper::checkArrayIsUniqueValues($company['governorates']);
        if(!$check)
            return Helper::responseError('governorates must be unique', [], 400);
        return $this->CompanyRepo->create($company);
    }

    /**
     * Display the Company.
     *
     * @param  \App\Models\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->CompanyRepo->show($id);
        return Helper::responseSuccess('Get company cuccessfully', $response);
    }

    /**
     * Update the Company in storage.
     *
     * @param  \Illuminate\Company\Update  $request
     * @param  \App\Models\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $company = $request->validated();
        $response = $this->CompanyRepo->update($id, $company);
        return Helper::responseSuccess('Update company successfully', $response);
    }

    /**
     * Remove the Company storage.
     *
     * @param  \App\Models\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $response = $this->CompanyRepo->delete($company);
        return Helper::responseSuccess('Delete company successfully', $response);
    }

    /**
     * Restore company.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $response = $this->CompanyRepo->restore($id);
        return Helper::responseSuccess('Restore company successfully', $response);
    }
}
