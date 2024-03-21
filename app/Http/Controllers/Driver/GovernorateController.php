<?php

namespace App\Http\Controllers\Driver;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\GovernorateRepository;

class GovernorateController extends Controller
{
    public function __construct(private GovernorateRepository $GovernorateRepo)
    {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->GovernorateRepo->getList($request->take);
        
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