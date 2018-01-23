<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 14:32
 */

namespace App\Services;


use App\Constants\ListType;
use App\Constants\QueryOrderTypes;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Lib\JSYService\TransactionServiceManager;
use App\Models\NotificationLog;
use App\ValueObject\NotificationLogListVO;

class NotificationLogService extends BaseService
{
	/**
	 * @param $userId
	 * @param $boundaryId
	 * @param $listType
	 * @param $limit
	 * @param $orderTypeString
	 * @return ServiceResult
	 */
    public function getList($userId, $boundaryId, $listType,$limit, $orderTypeString){
        $orderType = new QueryOrderTypes($orderTypeString);

        if($listType == ListType::NOTIFICATION_LOG_USER)
            return $this->executeTasks($this->_getUserLogsTasks($userId,$boundaryId,$limit,$orderType),true);
        else
        	return ServiceResult::withError(StatusCode::UNKNOWN,"Can't find method for list type {$listType}");

    }

    /**
     * @param $userId
     * @param $boundaryId
     * @param $limit
     * @param QueryOrderTypes $orderType
     * @return \Closure
     */
    private function _getUserLogsTasks($userId, $boundaryId, $limit, QueryOrderTypes $orderType):\Closure{
        /**
         * @return ServiceResult
         * @throws \TypeError
         */
        return function() use ($userId,$boundaryId,$limit,$orderType):ServiceResult{
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