<?php
namespace App\Services;

use App\Helper\Helper;
use App\Models\User;
use App\Helper\Utilities;

class SendNotification
{
   
    public static function sendNotificationToAllDrivers($title, $message, $player_ids)
    {
        return Helper::sendNotifications($title, $message, $player_ids);
    }
    
}