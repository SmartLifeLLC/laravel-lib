<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 14:58
 */

namespace App\Services;


use App\Lib\JSYService\ServiceManagerFactory;
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
        return $this->executeTasks($this->_getSwitchBlockStatusTasks($userId,$targetUserId,$isBlockOn),true);
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
				//Delete reactions between block users.
            //Cancel block status.
            }else{
                $blockUserModel->deleteBlock($userId, $targetUserId);
                $blockedUserModel->deleteBlocked($targetUserId,$userId);
            }
            return  ServiceResult::withResult(true,null);
        };

    }

	/**
	 * @param $userId
	 * @param $page
	 * @param $limit
	 * @return ServiceResult
	 */
	public function getList($userId, $page, $limit){
		return $this->executeTasks(function() use ($userId,$page,$limit){
			$blockUsers = (new BlockUser())->getBlockUsers($userId,$page,$limit);
			return ServiceResult::withResult($blockUsers);
		});
	}
}