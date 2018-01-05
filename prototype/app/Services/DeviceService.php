<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 13:29
 */

namespace App\Services;


use App\Lib\JSYService\NormalServiceManager;
use App\Lib\JSYService\ServiceManagerFactory;
use App\Lib\JSYService\ServiceResult;
use App\Models\Device;

class DeviceService extends BaseService
{

    /**
     * @param $userId
     * @param $deviceUuid
     * @param $notificationToken
     * @param $deviceType
     * @return ServiceResult
     */
    public function register($userId,$deviceUuid,$notificationToken,$deviceType):ServiceResult{
        return $this->executeTasks(
            function() use ($userId,$deviceUuid,$notificationToken,$deviceType) {
                $deviceModel = new Device();
                $result = $deviceModel->register($userId,$deviceUuid,$notificationToken,$deviceType);
            return ServiceResult::withResult($result);
        });
    }
}