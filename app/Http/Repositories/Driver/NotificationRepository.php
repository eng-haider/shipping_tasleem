<?php
namespace App\Http\Repositories\Driver;

use App\Models\Notification;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Repositories\BaseRepository;
use App\Models\DriverNotification;

class NotificationRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Notification());
    }
    public function getMyNotificationsList($take = 10)
    {
        $result = QueryBuilder::for($this->model)
                                ->whereHas('drivers', function ($query) {
                                    $query->where('driver_id', auth('driver')->user()->id);
                                })
                                ->allowedIncludes($this->model->relations)
                                ->allowedFilters($this->model->columns)
                                ->allowedSorts($this->model->columns);
        return $result->paginate($take);
    } 
    public function seenNotifications($data)
    {
        return DriverNotification::whereIn('notification_id', $data['notificationIds'])->update(['is_seen' => 1]);
    }
} 