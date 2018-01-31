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

    public function getData($userId, $deviceType, $notificationToken, $created):ServiceResult{
        return $this->executeTasks(function() use($userId, $deviceType, $notificationToken, $created) {
            $contributionId = (new Device())->translateGetId($userId, $deviceType, $notificationToken, $created);
            return ServiceResult::withResult($contributionId);
        },true);
    }
}