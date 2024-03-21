<?php

namespace App\Http\Controllers\Company;

use App\Helper\Helper;
use App\Models\DriverVehicle;
use App\Http\Controllers\Controller;
use App\Http\Requests\Index\Pagination;
use App\Http\Requests\DriverVehicle\Create;
use App\Http\Requests\DriverVehicle\Update;
use App\Http\Repositories\Company\DriverVehicleRepository;

class DriverVehicleController extends Controller
{
    public function __construct(private DriverVehicleRepository $DriverVehicleRepo)
    {
        $this->middleware(['permissions:company-get-driverVehicle'])->only(['index']);
        $this->middleware(['permissions:company-store-driverVehicle'])->only(['store']);
        $this->middleware(['permissions:company-show-driverVehicle'])->only(['show']);
        $this->middleware(['permissions:company-update-driverVehicle'])->only(['update']);
        $this->middleware(['permissions:company-delete-driverVehicle'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->DriverVehicleRepo->getList($request->take);
    }

    /**
     * Store a newly created DriverVehicle in storage.
     *
     * @param  \Illuminate\Http\DriverVehicle\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $driverVehicle = $request->validated();
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
    public function show($id)
    {
        $response = $this->DriverVehicleRepo->show($id);
        return Helper::responseSuccess('Get DriverVehicle successfully', $response);
    }

    /**
     * Update the DriverVehicle in storage.
     *
     * @param  \Illuminate\DriverVehicle\Update  $request
     * @param  \App\Models\Models\DriverVehicle  $driverVehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $driverVehicle = $request->validated();
        if($request->hasFile('image')){
            $driverVehicle['image'] = $request->file('image')->store('driver-vehicles');
        }
        $response = $this->DriverVehicleRepo->update($id, $driverVehicle);
        return Helper::responseSuccess('Update DriverVehicle successfully', $response);
    }

    /**
     * Remove the DriverVehicle storage.
     *
     * @param  \App\Models\Models\DriverVehicle  $driverVehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(DriverVehicle $driverVehicle)
    {
        $response = $this->DriverVehicleRepo->delete($driverVehicle);
        return Helper::responseSuccess('Delete DriverVehicle successfully', $response);
    }
}
