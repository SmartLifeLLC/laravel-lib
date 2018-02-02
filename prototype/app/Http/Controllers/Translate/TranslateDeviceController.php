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

class TranslateDeviceController extends Controller
{
    /**
     * @return null|string
     */
    public function translatePreviousData()
    {
        $devices = (new NotificationToken())->getData();

        foreach ($devices as $device) {
            $userId = $device->user_id;
            $deviceType = 'iPhone';
            $notificationToken = $device->device_token;
            $created = $device->created_at;
            $updated = $device->updated_at;

            $serviceResult = (new PreviousDeviceService())->getData($userId, $deviceType, $notificationToken, $created);

            if ($serviceResult->getDebugMessage() != NULL) return $serviceResult->getDebugMessage();
        }
        return 'SUCCESS';
    }
}