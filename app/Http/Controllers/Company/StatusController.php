<?php

namespace App\Http\Controllers\Company;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\StatusRepository;

class StatusController extends Controller
{
    public function __construct(private StatusRepository $StatusRepo)
    {
        $this->middleware(['permissions:company-get-status'])->only(['index']);
        $this->middleware(['permissions:company-show-status'])->only(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->StatusRepo->getList($request->take);
        
    }

    /**
     * Display the Status.
     *
     * @param  \App\Models\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->StatusRepo->show($id);
        return Helper::responseSuccess('Get status successfully', $response);
    }
}