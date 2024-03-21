<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Governorate;
use App\Http\Requests\Index\Pagination;
use App\Http\Requests\Governorate\Create;
use App\Http\Requests\Governorate\Update;
use App\Http\Repositories\GovernorateRepository;
use App\Http\Requests\Governorate\CreateToCompany;

class GovernorateController extends Controller
{
    public function __construct(private GovernorateRepository $GovernorateRepo)
    {
        $this->middleware(['permissions:get-governorate'])->only(['index']);
        $this->middleware(['permissions:store-governorate'])->only(['store', 'CreateGovernorateToCompany']);
        $this->middleware(['permissions:show-governorate'])->only(['show']);
        $this->middleware(['permissions:update-governorate'])->only(['update']);
        $this->middleware(['permissions:delete-governorate'])->only(['destroy']);
    }

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
     * Store a newly created Governorate in storage.
     *
     * @param  \Illuminate\Http\Governorate\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $governorate = $request->validated();
        $response = $this->GovernorateRepo->create($governorate);
        return Helper::responseSuccess('Create governorate successfully', $response);
    }

    /**
     * Store a newly created Governorate in storage.
     *
     * @param  \Illuminate\Http\Governorate\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function CreateGovernorateToCompany(CreateToCompany $request)
    {
        $governorate = $request->validated();
        $response = $this->GovernorateRepo->CreateGovernorateToCompany($governorate);
        return Helper::responseSuccess('Create governorate successfully', $response);
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

    /**
     * Update the Governorate in storage.
     *
     * @param  \Illuminate\Governorate\Update  $request
     * @param  \App\Models\Models\Governorate  $governorate
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $governorate = $request->validated();
        $response = $this->GovernorateRepo->update($id, $governorate);
        return Helper::responseSuccess('Update governorate successfully', $response);
    }

    /**
     * Remove the Governorate storage.
     *
     * @param  \App\Models\Models\Governorate  $governorate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Governorate $governorate)
    {
        $response = $this->GovernorateRepo->delete($governorate);
        return Helper::responseSuccess('Delete governorate successfully', $response);
    }
}
