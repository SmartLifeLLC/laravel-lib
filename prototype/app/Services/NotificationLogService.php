<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 14:32
 */

namespace App\Services;


use App\Constants\QueryOrderTypes;
use App\Lib\JSYService\ServiceResult;
use App\Lib\JSYService\TransactionServiceManager;
use App\Models\NotificationLog;
use App\ValueObject\NotificationLogListVO;

class NotificationLogService extends BaseService
{
    /**
     * @param $userId
     * @param $boundaryId
     * @param $limit
     * @param $orderTypeString
     * @return ServiceResult
     */
    public function getLogs($userId, $boundaryId, $limit, $orderTypeString){
        $orderType = new QueryOrderTypes($orderTypeString);
        return $this->executeTasks($this->_getLogsTasks($userId,$boundaryId,$limit,$orderType),true);
    }

    /**
     * @param $userId
     * @param $boundaryId
     * @param $limit
     * @param QueryOrderTypes $orderType
     * @return \Closure
     */
    private function _getLogsTasks($userId,$boundaryId,$limit,QueryOrderTypes $orderType):\Closure{
        /**
         * @return ServiceResult
         * @throws \TypeError
         */
        return function() use ($userId,$boundaryId,$limit,$orderType){
            $notificationModel = new NotificationLog();
            $count = $notificationModel->getUnreadCount($userId);
            $notificationLogs = $notificationModel->getLogs($userId,$boundaryId,$limit,$orderType);
            $unreadIds = [];
            foreach($notificationLogs as $notificationLog){
                if(empty($notificationLog->read_at)){
                    $unreadIds[] = $notificationLog->id;
                }
            }
            if(!empty($unreadIds)){ $notificationModel->updateReadDate($unreadIds);}
            $notificationLogListVo = new NotificationLogListVO($count,$notificationLogs->toArray());
            return ServiceResult::withResult($notificationLogListVo);
        };
    }


    /**
     * @param $userId
     * @return ServiceResult
     */
    public function getUnreadCount($userId){
        return $this->executeTasks(function() use ($userId){
            $count = (new NotificationLog())->getUnreadCount($userId);
            return ServiceResult::withResult($count);
        });
    }
}