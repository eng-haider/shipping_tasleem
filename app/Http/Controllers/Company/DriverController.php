<?php

namespace App\Http\Controllers\Company;

use App\Helper\Helper;
use App\Models\Driver;
use App\Http\Controllers\Controller;
use App\Services\SendMessageService;
use App\Http\Requests\Index\Pagination;
use App\Http\Requests\Driver\CreateDriverCompany;
use App\Http\Requests\Driver\UpdateDriverCompany;
use App\Http\Repositories\Company\DriverRepository;
use App\Http\Repositories\DriverDocumentRepository;


class DriverController extends Controller
{
    public function __construct(private DriverRepository $DriverRepo,private DriverDocumentRepository $driverDocumentRepo)
    {
        $this->middleware(['permissions:company-get-driver'])->only(['index']);
        $this->middleware(['permissions:company-store-driver'])->only(['store']);
        $this->middleware(['permissions:company-show-driver'])->only(['show']);
        $this->middleware(['permissions:company-update-driver'])->only(['update']);
        $this->middleware(['permissions:company-delete-driver'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->DriverRepo->getMyDriversList($request->take);
    }

    /**
     * Store a newly created Driver in storage.
     *
     * @param  \Illuminate\Http\Driver\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDriverCompany $request)
    {

        $driver = $request->validated();
        if ($request->hasFile('image')) {
            $driver['image'] = $request->file('image')->store('driver-images');
        }
        $driver['company_id'] = auth('user')->user()->company->company_id;
       

        $service = new SendMessageService();
        return $this->DriverRepo->create($driver);
        $service->sendCreateDriverMessage($driver['phone'], 'نودّ أن نُعلمكم بانضمامكم إلى نظام تسليم لدينا بنجاح.\n يرجى تحميل تطبيق تسليم من خلال الرابط التالي:\n https://tanoma.page.link/DQbR');
    }

    /**
     * Display the Driver.
     *
     * @param  \App\Models\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->DriverRepo->showMyDriver($id);
        return Helper::responseSuccess('Get driver successfully', $response);
    }

    /**
     * Update the Driver in storage.
     *
     * @param  \Illuminate\Driver\Update  $request
     * @param  \App\Models\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDriverCompany $request, $id)
    {
        $driver = $request->validated();
        if ($request->hasFile('image')) {
            $driver['image'] = $request->file('image')->store('driver-images');
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
        if($driver->orders->count() > 0)

        {
          $response = $this->DriverRepo->update($id, ['is_active' => 0]);
          return Helper::responseSuccess('Driver inactive, because this driver has orders', $response);

        }
        else{
          $response = $this->DriverRepo->forceDelete($id);
          return Helper::responseSuccess('Delete driver successfully', $response);
        }
    }
}
