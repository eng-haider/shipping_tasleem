<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Document;
use App\Models\DriverDocument;

use App\Http\Requests\Document\Create;
use App\Http\Requests\Document\Update;
use App\Http\Requests\Index\Pagination;
use App\Http\Repositories\DocumentRepository;

class DocumentController extends Controller
{
    
    public function __construct(private DocumentRepository $DocumentRepo)
    {
        $this->middleware(['permissions:get-document'])->only(['index']);
        $this->middleware(['permissions:store-document'])->only(['store']);
        $this->middleware(['permissions:show-document'])->only(['show']);
        $this->middleware(['permissions:update-document'])->only(['update']);
        $this->middleware(['permissions:delete-document'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->DocumentRepo->getList($request->take);
        
    }

    /**
     * Store a newly created Status in storage.
     *
     * @param  \Illuminate\Http\Status\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        // $document = $request->validated();
        // if($request->hasFile('front_image')){
        //     $document['front_image'] = $request->file('front_image')->store('document-images');
        // }

        // if($request->hasFile('back_image')){
        //     $document['back_image'] = $request->file('back_image')->store('document-images');
        // }

        // $response = $this->DocumentRepo->create($document);
        // $response->createDriverDocumentsForAllDrivers($response->id);
        // return Helper::responseSuccess('Create status successfully', $response);
    }

    /**
     * Display the Status.
     *
     * @param  \App\Models\Models\Status  $document
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->DocumentRepo->show($id);
        return Helper::responseSuccess('Get status successfully', $response);
    }

    /**
     * Update the Status in storage.
     *
     * @param  \Illuminate\Status\Update  $request
     * @param  \App\Models\Models\Status  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Update $request, $id)
    {
        // $document = $request->validated();

        // if($request->hasFile('front_image')){
            

        //      //update driver doc for driver if file null or contain defual Document image
        //     $frontImageFileName= $request->file('front_image')->store('document-images');
        //     $document['front_image']= $frontImageFileName;
        //     DriverDocument::where('document_id', $id)
        //     ->where('title', 'Front side image')
        //     ->where(function ($query) {
        //         $query->whereNull('file')
        //         ->orWhere(function ($subquery)  {
        //             $subquery->where('file', 'NOT LIKE', '%driver-document-images%');
        //         });
        //     })
        //     ->update(['file' =>$frontImageFileName]);


        // }

        // if($request->hasFile('back_image')){

        //     //update driver doc for driver if file null or contain defual Document image
        //     $frontImageFileName= $request->file('back_image')->store('document-images');
        //     $document['back_image']= $frontImageFileName;
        //     DriverDocument::where('document_id', $id)
        //     ->where('title', 'Back side image')
        //     ->where(function ($query) {
        //         $query->whereNull('file')
        //         ->orWhere(function ($subquery)  {
        //             $subquery->where('file', 'NOT LIKE', '%driver-document-images%');
        //         });
        //     })
        //     ->update(['file' =>$frontImageFileName]);



        // }
        // $response = $this->DocumentRepo->update($id, $document);
        // return Helper::responseSuccess('Update status successfully', $response);
    }

    /**
     * Remove the Status storage.
     *
     * @param  \App\Models\Models\Status  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        // $response = $this->DocumentRepo->delete($document);
        // return Helper::responseSuccess('Delete status successfully', $response);
    }
}
