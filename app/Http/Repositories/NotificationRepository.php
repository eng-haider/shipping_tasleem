<?php
namespace App\Http\Repositories;

use App\Helper\Helper;
use App\Models\Driver;
use Mockery\Matcher\Not;
use App\Models\Notification;
use App\Models\DriverNotification;
use App\Services\SendNotification;
use Illuminate\Support\Facades\DB;

class NotificationRepository extends BaseRepository{
    public function __construct()
    {
        parent::__construct(new Notification());
    }
    public function create($data)
    {
        DB::beginTransaction();
        try {
            $driverIds = $data['driverIds'];
            $keys = Driver::whereIn('id', $driverIds)->pluck('player_id')->toArray();
            $service = new SendNotification();
            unset($data['driverIds']);
            $service->sendNotificationToAllDrivers($data['title'], $data['description'] , $keys);
            $notify = $this->model->create($data);
            foreach ($driverIds as $driverId) {
                DriverNotification::create([
                    'driver_id' => $driverId,
                    'notification_id' => $notify->id,
                ]);
            }
            DB::commit();
            return Helper::responseSuccess('Create Notification successfully', $notify);
        } catch (\Exception $e) {
            DB::rollBack();
            return Helper::responseError($e->getMessage(),[], 500);
        }
    }
}