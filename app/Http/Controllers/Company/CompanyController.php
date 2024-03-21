<?php

namespace App\Http\Controllers\Company;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Update;
use App\Http\Repositories\CompanyRepository;

class CompanyController extends Controller
{
    public function __construct(private CompanyRepository $CompanyRepo)
    {
        $this->middleware(['permissions:show-my-company'])->only(['show']);
        $this->middleware(['permissions:update-my-company'])->only(['update']);
    }

    /**
     * Display the Company.
     *
     * @param  \App\Models\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $response = $this->CompanyRepo->show(auth('user')->user()->company->company_id);
        return Helper::responseSuccess('Get My company successfully', $response);
    }

    /**
     * Update the Company in storage.
     *
     * @param  \Illuminate\Company\Update  $request
     * @param  \App\Models\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request)
    {
        $company = $request->validated();
        $response = $this->CompanyRepo->update(auth('user')->user()->company->company_id, $company);
        return Helper::responseSuccess('Update company successfully', $response);
    }
}
