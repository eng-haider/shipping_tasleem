<?php

namespace App\Http\Controllers;

use App\Models\Version;
use Illuminate\Http\Request;
use App\Http\Requests\Version\Create;
use App\Http\Requests\Version\Update;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\VersionRepository;
use Symfony\Component\HttpFoundation\Response;

class VersionController extends Controller
{
    public function __construct(private VersionRepository $VersionRepo)
    {
        $this->middleware(['permissions:get-version'])->only(['index']);
        $this->middleware(['permissions:store-version'])->only(['store']);
        $this->middleware(['permissions:show-version'])->only(['show']);
        $this->middleware(['permissions:update-version'])->only(['update']);
        $this->middleware(['permissions:delete-version'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->VersionRepo->getList($request->take);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $version = $request->validated();
        $response = $this->VersionRepo->create($version);
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Models\Version  $version
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->VersionRepo->show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\Version  $version
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $version = $request->validated();
        $response = $this->VersionRepo->update($id, $version);
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Models\Version  $version
     * @return \Illuminate\Http\Response
     */
    public function destroy(Version $version)
    {
        $response = $this->VersionRepo->delete($version);
        return response()->json($response, Response::HTTP_OK);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Versions  $Versions
     * @return \Illuminate\Http\Response
     */
    public function getPublicVersion($version)
    {
        return $this->VersionRepo->getVersion($version);
    }
}
