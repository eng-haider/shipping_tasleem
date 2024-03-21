<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Driver;
use App\Models\DriverVehicle;
use App\Http\Requests\Driver\Create;
use App\Http\Requests\Driver\Update;
use App\Services\SendMessageService;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\DriverRepository;
use App\Http\Repositories\DriverDocumentRepository;
class DriverController extends Controller
{
    public function __construct(private DriverRepository $DriverRepo,private DriverDocumentRepository $driverDocumentRepo)
    {
        $this->middleware(['permissions:get-driver'])->only(['index']);
        // $this->middleware(['permissions:store-driver'])->only(['store']);
        $this->middleware(['permissions:show-driver'])->only(['show']);
        $this->middleware(['permissions:update-driver'])->only(['update']);
        $this->middleware(['permissions:delete-driver'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->DriverRepo->getList($request->take);
    }

    /**
     * Store a newly created Driver in storage.
     *
     * @param  \Illuminate\Http\Driver\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $driver = $request->validated();
        if($request->has('password')){
            $driver['password'] = bcrypt($request->password);
        }
        if ($request->hasFile('image')) {
            $driver['image'] = $request->file('image')->store('driver-images');
        }
        $response = $this->DriverRepo->create($driver);
     
        return Helper::responseSuccess('Create driver successfully', $response);
    }

    /**
     * Display the Driver.
     *
     * @param  \App\Models\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->DriverRepo->show($id);
        return Helper::responseSuccess('Get driver successfully', $response);
    }

    /**
     * Display the Driver.
     *
     * @param  \App\Models\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function verifiedDriver(Driver $driver)
    {
        if(!$driver->is_verified){
            $this->DriverRepo->update($driver->id, ['is_verified' => 1]);
            return Helper::responseSuccess('Driver verified successfully', $driver);
        }else{
            $this->DriverRepo->update($driver->id, ['is_verified' => 0]);
            return Helper::responseSuccess('Driver unverified successfully', $driver);
        }
    }
    /**
     * Display the Driver.
     *
     * @param  \App\Models\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function blockedDriver(Driver $driver)
    {
        if(!$driver->is_block){
            $this->DriverRepo->update($driver->id, ['is_block' => 1]);
            return Helper::responseSuccess('Driver blocked successfully', $driver);
        }else{
            $this->DriverRepo->update($driver->id, ['is_block' => 0]);
            return Helper::responseSuccess('Driver unblocked successfully', $driver);
        }
    }

    /**
     * Update the Driver in storage.
     *
     * @param  \Illuminate\Driver\Update  $request
     * @param  \App\Models\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        $driver = $request->validated();
        if($request->has('password')){
            $driver['password'] = bcrypt($request->password);
        }
        if ($request->hasFile('image')) {
            $driver['image'] = $request->file('image')->store('drivers');
        }
        $response = $this->DriverRepo->update($id, $driver);
        return Helper::responseSuccess('Update driver successfully', $response);
    }

    /**
     * Remove the Driver storage.
     *
     * @param  \App\Models\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $driver = $this->DriverRepo->show($id);
        if($driver->orders->count() > 0){
            $this->DriverRepo->update($id, ['is_active' => 0]);
            return Helper::responseSuccess('Driver inactive, because this driver has orders', []);
        }
           else{
            $response = $this->DriverRepo->forceDelete($id);
            return Helper::responseSuccess('Delete driver successfully', $response);
           }
       
    }
}
