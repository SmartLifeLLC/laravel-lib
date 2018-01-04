<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 18:18
 */

namespace App\Services;


use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Lib\JSYService\TransactionServiceManager;
use App\Models\Follow;
use App\Models\Follower;
use App\Models\BlockUser;
use App\Models\BlockedUser;
use App\ValueObject\SwitchFollowResultVO;

class FollowService extends BaseService
{
    /**
     * @param $userId
     * @param $targetUserId
     * @param $isFollowOn
     * @return ServiceResult (data is instance of SwitchFollowResultVO)
     */
    public function switchFollowStatus($userId,$targetUserId,$isFollowOn):ServiceResult{
        $manager = new TransactionServiceManager();
        $manager->setTasks($this->_getSwitchBlockStatusTasks($userId,$targetUserId,$isFollowOn),$this);
        $manager->execute();
        $serviceResult = $manager->getServiceResult();
        return $serviceResult;
    }


    /**
     * @param $userId
     * @param $targetUserId
     * @param $isFollowOn
     * @return \Closure
     */
    private function _getSwitchBlockStatusTasks($userId, $targetUserId, $isFollowOn):\Closure{
        return function()use($userId,$targetUserId,$isFollowOn):ServiceResult{
            //Prepare models.
            $followModel = new Follow();
            $followerModel = new Follower();
            $blockUserModel = new BlockUser();

            //Check is block status.
            if($isFollowOn && $blockUserModel->isBlockStatus($userId,$targetUserId)){
                return ServiceResult::withError(StatusCode::FOLLOW_ADD_ERROR,"User {$userId} and target user {$targetUserId} is block status.");
            }
            $switchFollowResultVO = $followModel->switchFollow($userId, $targetUserId,$isFollowOn);
            $switchFollowerResultVO = $followerModel->switchFollower($targetUserId,$userId,$isFollowOn);

            return  ServiceResult::withResult($switchFollowResultVO,SwitchFollowResultVO::class);
        };

    }
}