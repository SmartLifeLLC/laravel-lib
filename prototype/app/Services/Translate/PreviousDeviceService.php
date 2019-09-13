<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/31
 * Time: 22:18
 */

namespace App\Services\Translate;

use App\Models\Device;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousDeviceService extends BaseService
{
    /**
     * @param $userId
     * @param $deviceType
     * @param $notificationToken
     * @param $created
     * @return ServiceResult
     */
    public function getData($userId, $deviceType, $notificationToken, $created, $updated):ServiceResult{
        return $this->executeTasks(function() use($userId, $deviceType, $notificationToken, $created, $updated) {
            $contributionId = (new Device())->translateGetId($userId, $deviceType, $notificationToken, $created, $updated);
            return ServiceResult::withResult($contributionId);
        },true);
    }
}