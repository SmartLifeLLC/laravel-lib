<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 14:58
 */

namespace App\Services;


use App\Lib\JSYService\TransactionServiceManager;
use App\Lib\JSYService\ServiceResult;
use App\Models\Follow;
use App\Models\Follower;
use App\Models\BlockedUser;
use App\Models\BlockUser;
class BlockService extends BaseService
{


    /**
     * @param $userId
     * @param $targetUserId
     * @param $isBlockOn
     * @return ServiceResult (Always return true.)
     */
    public function switchBlockStatus($userId,$targetUserId,$isBlockOn){
        $manager = new TransactionServiceManager();
        $manager->setTasks($this->_getSwitchBlockStatusTasks($userId,$targetUserId,$isBlockOn),$this);
        $manager->execute();
        $serviceResult = $manager->getServiceResult();
        return $serviceResult;
    }

    /**
     * @param $userId
     * @param $targetUserId
     * @param $isBlockOn
     * @return \Closure
     */
    private function _getSwitchBlockStatusTasks($userId,$targetUserId,$isBlockOn):\Closure{
        return function()use($userId,$targetUserId,$isBlockOn){
            //Prepare models.
            $followModel = new Follow();
            $followerModel = new Follower();
            $blockUserModel = new BlockUser();
            $blockedUserModel = new BlockedUser();

            //Cancel follow and follower status.
            if($isBlockOn && $followModel->isFollowStatus($userId,$targetUserId)){
                $followModel->switchFollow($userId,$targetUserId,false);
                $followModel->switchFollow($targetUserId,$userId,false);
                $followerModel->switchFollower($targetUserId,$userId,false);
                $followerModel->switchFollower($userId,$targetUserId,false);
            }

            //Make block status.
            if($isBlockOn) {
                $blockUserModel->createBlock($userId, $targetUserId);
                $blockedUserModel->createBlocked($targetUserId,$userId);

            //Cancel block status.
            }else{
                $blockUserModel->deleteBlock($userId, $targetUserId);
                $blockedUserModel->deleteBlocked($targetUserId,$userId);
            }
            return  ServiceResult::withResult(true,null);
        };

    }
}