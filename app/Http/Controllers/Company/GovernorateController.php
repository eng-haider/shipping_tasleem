<?php

namespace App\Http\Controllers\Company;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\Company\GovernorateRepository;

class GovernorateController extends Controller
{
    public function __construct(private GovernorateRepository $GovernorateRepo)
    {
        $this->middleware(['permissions:company-get-governorate'])->only(['index']);
        $this->middleware(['permissions:company-show-governorate'])->only(['show']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->GovernorateRepo->getMyCompanyGovernorateList($request->take);
    }

    /**
     * Display the Governorate.
     *
     * @param  \App\Models\Models\Governorate  $governorate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->GovernorateRepo->show($id);
        return Helper::responseSuccess('Get governorate successfully', $response);
    }
}