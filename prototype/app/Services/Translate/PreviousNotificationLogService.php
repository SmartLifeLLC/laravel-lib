<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:05
 */

namespace App\Services\Translate;

use App\Models\NotificationLog;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousNotificationLogService extends BaseService
{
    /**
     * @param $logData
     * @return ServiceResult
     */
    public function getData($logData): ServiceResult
    {
        return $this->executeTasks(function () use ($logData) {
            $logId = (new NotificationLog())->translateGetId($logData);
            return ServiceResult::withResult($logId);
        }, true);
    }
}