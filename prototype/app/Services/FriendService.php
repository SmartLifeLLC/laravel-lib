<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 18:18
 */

namespace App\Services;


use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceManagerFactory;
use App\Lib\JSYService\ServiceResult;
use App\Lib\JSYService\TransactionServiceManager;
use App\Models\Contribution;
use App\Models\ContributionInterestReaction;
use App\Models\Device;
use App\Models\Follow;
use App\Models\Follower;
use App\Models\BlockUser;
use App\Models\BlockedUser;
use App\Models\NotificationLog;
use App\Models\User;
use App\Services\Tasks\NotificationTask\Factory\FollowNotificationFactory;
use App\ValueObject\FollowOrFollowerGetListResultVO;
use App\ValueObject\SwitchFollowResultVO;

class FriendService extends BaseService
{
    /**
     * @param $userId
     * @param $targetUserId
     * @param $isFollowOn
     * @return ServiceResult (data is instance of SwitchFollowResultVO)
     */
    public function switchFollowStatus($userId,$targetUserId,$isFollowOn):ServiceResult{
        return $this->executeTasks($this->_getSwitchBlockStatusTasks($userId,$targetUserId,$isFollowOn),true);
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

            //Send Notification
            if($switchFollowResultVO->isFirstTime()){
	            $user = (new User())->getSimpleUserInfo($userId);
				$notificationTargetUsers = (new Device())->getNotificationTargetUsers([$targetUserId]);

				$factory = new FollowNotificationFactory();
				$notification =
				$factory
					->setUserName($user['user_name'])
					->setFromUserId($userId)
					->setTargetUsers($notificationTargetUsers)
					->create();

				//Send
	            $notification->send();
	            //insert
	            (new NotificationLog())->saveData($notification->saveData());

            }
            $switchFollowerResultVO = $followerModel->switchFollower($targetUserId,$userId,$isFollowOn);
            return  ServiceResult::withResult($switchFollowResultVO,SwitchFollowResultVO::class);
        };
    }

	/**
	 * @param $userId
	 * @param $ownerId
	 * @param $page
	 * @param $limit
	 * @return ServiceResult
	 */
    public function getFollowList($userId,$ownerId,$page,$limit):ServiceResult{
    	return $this->executeTasks(function ()use ($userId,$ownerId,$page,$limit){
    		$blockModel = new BlockUser();
    		if($blockModel->isBlockStatus($userId,$ownerId))
    			return ServiceResult::withBlockStatusError($userId,$ownerId);

    		$blockList = $blockModel->getBlockAndBlockedUserIds($userId,$ownerId);
    		$follow = new Follow();
    		$followCount = $follow->getCountForUser($ownerId,$blockList);
		    $contributionCount = (new Contribution())->getCountForUser($ownerId);
		    $followerCount = (new Follower())->getCountForUser($ownerId,$blockList);
			$interestCount = (new ContributionInterestReaction())->getCountForUser($ownerId,$blockList);
		    $followList = [];
		    $hasNext = 0;

			if($followCount > 0 ) {
				$followList = $follow->getList($userId,$ownerId,$page,$limit);
				$hasNext = $follow->getHasNext($limit,$page,$followCount);
			}

			$result = new FollowOrFollowerGetListResultVO($contributionCount,$interestCount,$followCount,$followerCount,$followList,$hasNext);
			return ServiceResult::withResult($result);
	    });
    }


	/**
	 * @param $userId
	 * @param $ownerId
	 * @param $page
	 * @param $limit
	 * @return ServiceResult
	 */
	public function getFollowerList($userId,$ownerId,$page,$limit):ServiceResult{
		return $this->executeTasks(function ()use ($userId,$ownerId,$page,$limit){
			$blockList = (new BlockUser())->getBlockAndBlockedUserIds($userId,$ownerId);
			$followCount = (new Follow())->getCountForUser($ownerId,$blockList);
			$contributionCount = (new Contribution())->getCountForUser($ownerId);
			$follower = new Follower();
			$followerCount = $follower->getCountForUser($ownerId,$blockList);
			$interestCount = (new ContributionInterestReaction())->getCountForUser($ownerId,$blockList);
			$followerList = [];
			$hasNext = 0;

			if($followerCount > 0 ) {
				$followerList = $follower->getList($userId,$ownerId,$page,$limit);
				$hasNext = $follower->getHasNext($limit,$page,$followerCount);
			}

			$result = new FollowOrFollowerGetListResultVO($contributionCount,$interestCount,$followCount,$followerCount,$followerList,$hasNext);
			return ServiceResult::withResult($result);
		});
	}
}