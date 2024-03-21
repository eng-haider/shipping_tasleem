<?php

namespace App\Http\Controllers\Driver;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Index\Pagination;
use App\Http\Requests\Notification\Update;
use App\Http\Repositories\Driver\NotificationRepository;

class NotificationController extends Controller
{
    public function __construct(private NotificationRepository $NotificationRepo)
    {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pagination $request)
    {
        $request->validated();
        return $this->NotificationRepo->getMyNotificationsList($request->take);
    }

    /**
     * Seen the Notification in storage.
     *
     * @param  \Illuminate\Notification\Update  $request
     * @param  \App\Models\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function seenNotifications(Update $request)
    {
        $notificationIds = $request->validated();
        $this->NotificationRepo->seenNotifications($notificationIds);
        return Helper::responseSuccess('Update Notification successfully', []);
    }

}