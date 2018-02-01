<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/31
 * Time: 22:06
 */

namespace App\Http\Controllers\Translate;

use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousDeviceJsonView;
use App\Models\Old\NotificationToken;
use App\Services\Translate\PreviousDeviceService;
use DB;

class TranslateDeviceController extends Controller
{
    /**
     * @return array
     */
    public function translatePreviousData(){
        $results = array();

        $devices = (new NotificationToken())->getData();

        foreach ($devices as $device) {
            $userId = $device->user_id;
            $deviceType = $device->registed_app_info;
            $notificationToken = $device->device_token;
            $created = $device->created_at;

            $serviceResult = (new PreviousDeviceService())->getData($userId, $deviceType, $notificationToken, $created);

            $jsonView = (new PreviousDeviceJsonView($serviceResult));
            $results[] = $this->responseJson($jsonView);
        }
        return $results;
    }
}