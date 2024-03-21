<?php

namespace App\Http\Controllers\Driver;

use App\Helper\Helper;
use App\Models\Document;
use App\Models\DriverDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\DriverDocument\DriverCreate;
use App\Http\Requests\DriverDocument\DriverUpdate;
use App\Http\Repositories\Driver\DriverDocumentRepository;
use App\Http\Requests\Index\Pagination;

class DriverDocumentController extends Controller
{
    public function __construct(private DriverDocumentRepository $DriverDocumentRepo)
    {}

    /**
     * Store a newly created DriverDocument in storage.
     *
     * @param  \Illuminate\Http\DriverDocument\Create;  $request
     * @return \Illuminate\Http\Response
     */

     public function index()
     {
        return $this->DriverDocumentRepo->getMyDriverDocumentsList();
     }

     /**
      * Store a newly created DriverDocument in storage.
      *
      * @param  \Illuminate\Http\DriverDocument\Create;  $request
      * @return \Illuminate\Http\Response
      */
 
      public function getDocument(Pagination $request)
      {
         return $this->DriverDocumentRepo->getDocument($request->take);
      }


    public function store(DriverCreate $request)
    {
        // $driverDocument = $request->validated();
        // $driverDocument['driver_id'] = auth('driver')->user()->id;
        // if ($request->hasFile('file')) {
        //     $driverDocument['file'] = $request->file('file')->store('driver-document-images');
        // }
        // $response = $this->DriverDocumentRepo->create($driverDocument);
        // return Helper::responseSuccess('Create DriverDocument successfully', $response);
    }

    /**
     * Display the DriverDocument.
     *
     * @param  \App\Models\Models\DriverDocument  $driverDocument
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->DriverDocumentRepo->showDocument(auth('driver')->user()->id, $id);
        return Helper::responseSuccess('Get DriverDocument successfully', $response);
    }

    /**
     * Update the DriverDocument in storage.
     *
     * @param  \Illuminate\DriverDocument\Update  $request
     * @param  \App\Models\Models\DriverDocument  $driverDocument
     * @return \Illuminate\Http\Response
     */
    public function update(DriverUpdate $request, DriverDocument $driverDocument)
    {
        $document = $request->validated();
      
        if($driverDocument->driver_id != auth('driver')->user()->id)
            return Helper::responseError('DriverDocument not found', [], 404);
        if($driverDocument->status)
            return Helper::responseError('You cann`t change  document', [], 404);
        if($request->hasFile('file')) {
        if($driverDocument->file !== null && strpos($driverDocument->file, 'driver-document-images') !== false){
            unlink(public_path($driverDocument->file));
        }
            
            $document['file'] = $request->file('file')->store('driver-document-images');
        }
        $response = $this->DriverDocumentRepo->update($driverDocument->id, $document);
        return Helper::responseSuccess('Update DriverDocument successfully', $response);
    }
}
