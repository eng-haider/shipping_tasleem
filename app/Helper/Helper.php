<?php

namespace App\Helper;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Response;
use Ladumor\OneSignal\OneSignal;

class Helper
{
    public static function generateUuid()
    {
        return Uuid::uuid4()->toString();
    }
    //static function to retrun response with status code and message
    public static function responseSuccess($message, $data = null)
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }
    
    public static function responseError($message, $data = null, $code = 500)
    {
        $response = [
            'status' => false,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($response, $code);
    }
    public static function userAuth($guard)
    {
        return auth($guard)->user(); 
    }
    public static function checkObjectsHasDuplicate($objects, $key)
    {
        $collection = collect($objects);
        $grouped = $collection->groupBy($key);
        $duplicates = $grouped->filter(function ($group) {
            return $group->count() > 1;
        });
        if ($duplicates->isNotEmpty())
            return true;
        return false;
    }
    public static function checkArrayIsUniqueValues($array)
    {
         if(count($array) == count(array_unique($array)))
            return 1;
        return false;
    }   
    public static function sendNotifications($title, $message, $keys, $image = null)
    {
        if(count($keys)){
            $notificationData = [
                  'contents' => [
                      'en' => $message,
                  ],
                  'headings' => [
                      'en' => $title,
                  ],
                  'include_player_ids' => $keys,
                  'android_sound' => '', 
                //   'big_picture' => $image 
            ];
            return  OneSignal::sendPush($notificationData);
        }
      }
      public static function uploadImage($image, $folderName){
          $image_name = time().rand(1111,9999).'.'.date('Y-m-d').'.'.$image->getClientOriginalExtension();
          $image->move(public_path($folderName), $image_name);
          return $image_name;
      }
}
