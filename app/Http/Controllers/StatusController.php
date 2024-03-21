<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Status;
use App\Http\Requests\Status\Create;
use App\Http\Requests\Status\Update;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\StatusRepository;

class StatusController extends Controller
{
    public function __construct(private StatusRepository $StatusRepo)
    {
        $this->middleware(['permissions:get-status'])->only(['index']);
        $this->middleware(['permissions:store-status'])->only(['store']);
        $this->middleware(['permissions:show-status'])->only(['show']);
        $this->middleware(['permissions:update-status'])->only(['update']);
        $this->middleware(['permissions:delete-status'])->only(['destroy']);
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
     * Store a newly created Status in storage.
     *
     * @param  \Illuminate\Http\Status\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $status = $request->validated();
        if($request->hasFile('image')){
            $status['image'] = $request->file('image')->store('status-images');
        }
        $response = $this->StatusRepo->create($status);
        return Helper::responseSuccess('Create status successfully', $response);
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

    /**
     * Update the Status in storage.
     *
     * @param  \Illuminate\Status\Update  $request
     * @param  \App\Models\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $status = $request->validated();
        if($request->hasFile('image')){
            $status['image'] = $request->file('image')->store('status-images');
        }
        $response = $this->StatusRepo->update($id, $status);
        return Helper::responseSuccess('Update status successfully', $response);
    }

    /**
     * Remove the Status storage.
     *
     * @param  \App\Models\Models\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        $response = $this->StatusRepo->delete($status);
        return Helper::responseSuccess('Delete status successfully', $response);
    }
}
