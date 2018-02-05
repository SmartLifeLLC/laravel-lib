<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:06
 */

namespace App\Services\Translate;

use App\Models\ContributionReactionNotificationDelivery;
use App\Models\NotificationLog;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousReactionNotificationService extends BaseService
{
    /**
     * @param $userID
     * @param $contributionId
     * @param $type
     * @param $created
     * @return ServiceResult
     */
    public function getData($userID, $contributionId, $type, $created): ServiceResult
    {
        return $this->executeTasks(function () use ($userID, $contributionId, $type, $created) {
            $logId = (new ContributionReactionNotificationDelivery())->translateGetId($userID, $contributionId, $type, $created);
            return ServiceResult::withResult($logId);
        }, true);
    }
}