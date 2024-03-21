<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\DriverDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\Index\Pagination;
use App\Http\Requests\DriverDocument\Create;
use App\Http\Requests\DriverDocument\Status;
use App\Http\Requests\DriverDocument\Update;
use App\Http\Repositories\DriverDocumentRepository;

class DriverDocumentController extends Controller
{
    public function __construct(private DriverDocumentRepository $DriverDocumentRepo)
    {
        $this->middleware(['permissions:get-driverDocument'])->only(['index']);
        $this->middleware(['permissions:store-driverDocument'])->only(['store']);
        $this->middleware(['permissions:show-driverDocument'])->only(['show']);
        $this->middleware(['permissions:update-driverDocument'])->only(['update']);
        $this->middleware(['permissions:delete-driverDocument'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->DriverDocumentRepo->getMyDriverDocumentsList($request->take);
    }

    /**
     * Store a newly created DriverDocument in storage.
     *
     * @param  \Illuminate\Http\DriverDocument\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $driverDocument = $request->validated();
        if ($request->hasFile('file')) {
            $driverDocument['file'] = $request->file('file')->store('driver-document-images');
        }
        $response = $this->DriverDocumentRepo->create($driverDocument);
        return Helper::responseSuccess('Create DriverDocument successfully', $response);
    }


 

    /**
     * Display the DriverDocument.
     *
     * @param  \App\Models\Models\DriverDocument  $driverDocument
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->DriverDocumentRepo->show($id);
        return Helper::responseSuccess('Get DriverDocument successfully', $response);
    }

    /**
     * Update the DriverDocument in storage.
     *
     * @param  \Illuminate\DriverDocument\Update  $request
     * @param  \App\Models\Models\DriverDocument  $driverDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, DriverDocument  $driverDocument)
    {
        $document = $request->validated();
        if($request->hasFile('file')) {
            
            if($driverDocument->file !== null && strpos($driverDocument->file, 'driver-document-images') !== false){
                unlink(public_path($driverDocument->file));
            }
        }
        $response = $this->DriverDocumentRepo->update($driverDocument->id, $document);
        return Helper::responseSuccess('Update DriverDocument successfully', $response);
    }

    /**
     * Change DriverDocument status.
     *
     * @param  \App\Models\Models\DriverDocument  $driverDocument
     * @return \Illuminate\Http\Response
     */
     public function changeStatus(Status $request, $id)
     {
         $status = $request->validated();  
         $response = $this->DriverDocumentRepo->update($id, $status);
         return Helper::responseSuccess('Update DriverDocument successfully', $response);
     }

    /**
     * Remove the DriverDocument storage.
     *
     * @param  \App\Models\Models\DriverDocument  $driverDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(DriverDocument $driverDocument)
    {
        $response = $this->DriverDocumentRepo->delete($driverDocument);
        return Helper::responseSuccess('Delete DriverDocument successfully', $response);
    }
}
