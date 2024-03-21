<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\DriverVehicle;
use App\Http\Controllers\Controller;
use App\Http\Requests\Index\Pagination;
use App\Http\Requests\DriverVehicle\Create;
use App\Http\Requests\DriverVehicle\Status;
use App\Http\Requests\DriverVehicle\Update;
use App\Http\Repositories\DriverVehicleRepository;

class DriverVehicleController extends Controller
{
    public function __construct(private DriverVehicleRepository $DriverVehicleRepo)
    {
        $this->middleware(['permissions:get-DriverVehicle'])->only(['index']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        // return $this->DriverVehicleRepo->getMyDriverVehiclesList($request->take);
    }

    /**
     * Store a newly created DriverVehicle in storage.
     *
     * @param  \Illuminate\Http\DriverVehicle\Create;  $request
     * @return \Illuminate\Http\Response
     */
   

 

    /**
     * Display the DriverVehicle.
     *
     * @param  \App\Models\Models\DriverVehicle  $DriverVehicle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->DriverVehicleRepo->show($id);
        return Helper::responseSuccess('Get DriverVehicle successfully', $response);
    }

    /**
     * Update the DriverVehicle in storage.
     *
     * @param  \Illuminate\DriverVehicle\Update  $request
     * @param  \App\Models\Models\DriverVehicle  $DriverVehicle
     * @return \Illuminate\Http\Response
     */
  

    /**
     * Change DriverVehicle status.
     *
     * @param  \App\Models\Models\DriverVehicle  $DriverVehicle
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Remove the DriverVehicle storage.
     *
     * @param  \App\Models\Models\DriverVehicle  $DriverVehicle
     * @return \Illuminate\Http\Response
     */
   
}
