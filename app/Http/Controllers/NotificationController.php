<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Requests\Index\Pagination;
use App\Http\Requests\Notification\Create;
use App\Http\Requests\Notification\Update;
use App\Http\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    public function __construct(private NotificationRepository $NotificationRepo)
    {
        $this->middleware(['permissions:get-notification'])->only(['index']);
        $this->middleware(['permissions:store-notification'])->only(['store']);
        $this->middleware(['permissions:show-notification'])->only(['show']);
        $this->middleware(['permissions:update-notification'])->only(['update']);
        $this->middleware(['permissions:delete-notification'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->NotificationRepo->getList($request->take);
    }

    /**
     * Store a newly created Notification in storage.
     *
     * @param  \Illuminate\Http\Notification\Create;  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Create $request)
    {
        $notification = $request->validated();
        return $this->NotificationRepo->create($notification);
    }

    /**
     * Display the Notification.
     *
     * @param  \App\Models\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->NotificationRepo->show($id);
        return Helper::responseSuccess('Get Notification successfully', $response);
    }
}
