<?php

namespace App\Http\Controllers\Driver;

use App\Helper\Helper;
use App\Models\DriverVehicle;
use App\Http\Controllers\Controller;
use App\Http\Requests\DriverVehicle\Create;
use App\Http\Requests\DriverVehicle\Update;
use App\Http\Requests\DriverVehicle\DriverCreate;
use App\Http\Requests\DriverVehicle\DriverUpdate;
use App\Http\Repositories\Driver\DriverVehicleRepository;

class DriverVehicleController extends Controller
{
    public function __construct(private DriverVehicleRepository $DriverVehicleRepo)
    {}

    /**
     * Store a newly created DriverVehicle in storage.
     *
     * @param  \Illuminate\Http\DriverVehicle\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DriverCreate $request)
    {
        $driverVehicle = $request->validated();
        $driverVehicle['driver_id'] = auth('driver')->user()->id;
        if(auth('driver')->user()->vehicle)
            return Helper::responseError('You are already have vehicle', [], 400);
            
        if($request->hasFile('image')){
            $driverVehicle['image'] = $request->file('image')->store('driver-vehicles');
        }
        $response = $this->DriverVehicleRepo->create($driverVehicle);
        return Helper::responseSuccess('Create DriverVehicle successfully', $response);
    }

    /**
     * Display the DriverVehicle.
     *
     * @param  \App\Models\Models\DriverVehicle  $driverVehicle
     * @return \Illuminate\Http\Response
     */
    public function show(DriverVehicle $driverVehicle)
    {
        if($driverVehicle->driver_id != auth('driver')->user()->id){
            return Helper::responseError('You are not authorized to update this vehicle', [], 403);
        }
        $response = $this->DriverVehicleRepo->show($driverVehicle->id);
        return Helper::responseSuccess('Get DriverVehicle successfully', $response);
    }

    /**
     * Update the DriverVehicle in storage.
     *
     * @param  \Illuminate\DriverVehicle\Update  $request
     * @param  \App\Models\Models\DriverVehicle  $driverVehicle
     * @return \Illuminate\Http\Response
     */
    public function update(DriverUpdate $request,DriverVehicle $driverVehicle)
    {
        $vehicle = $request->validated();
        if($driverVehicle->driver_id != auth('driver')->user()->id){
            return Helper::responseError('You are not authorized to update this vehicle', [], 403);
        }
        if($request->hasFile('image')){
            $vehicle['image'] = $request->file('image')->store('driver-vehicles');
        }
        $response = $this->DriverVehicleRepo->update($driverVehicle->id, $vehicle);
        return Helper::responseSuccess('Update DriverVehicle successfully', $response);
    }
}
