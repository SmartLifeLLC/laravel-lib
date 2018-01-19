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
use App\Models\Feed;
use App\Models\FeedInterestReaction;
use App\Models\Follow;
use App\Models\Follower;
use App\Models\BlockUser;
use App\Models\BlockedUser;
use App\ValueObject\FollowOrFollowerGetListResultVO;
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
        return $this->executeTasks($this->_getSwitchBlockStatusTasks($userId,$targetUserId,$isFollowOn));
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

	/**
	 * @param $userId
	 * @param $page
	 * @param $limit
	 * @return ServiceResult
	 */
    public function getFollowList($userId,$page,$limit):ServiceResult{
    	return $this->executeTasks(function ()use ($userId,$page,$limit){
    		$follow = new Follow();
    		$followCount = $follow->getCountForUser($userId);
		    $feedCount = (new Feed())->getCountForUser($userId);
		    $followerCount = (new Follower())->getCountForUser($userId);
			$interestCount = (new FeedInterestReaction())->getCountForUser($userId);
		    $followList = [];
		    $hasNext = 0;

			if($followCount > 0 ) {
				$followList = $follow->getList($userId,$page,$limit);
				$hasNext = $follow->getHasNext($limit,$page,$followCount);
			}

			$result = new FollowOrFollowerGetListResultVO($feedCount,$interestCount,$followCount,$followerCount,$followList,$hasNext);
			return ServiceResult::withResult($result);
	    });
    }



	/**
	 * @param $userId
	 * @param $page
	 * @param $limit
	 * @return ServiceResult
	 */
	public function getFollowerList($userId,$page,$limit):ServiceResult{
		return $this->executeTasks(function ()use ($userId,$page,$limit){

			$followCount = (new Follow())->getCountForUser($userId);
			$feedCount = (new Feed())->getCountForUser($userId);
			$follower = new Follower();
			$followerCount = $follower->getCountForUser($userId);
			$interestCount = (new FeedInterestReaction())->getCountForUser($userId);
			$followerList = [];
			$hasNext = 0;

			if($followerCount > 0 ) {
				$followerList = $follower->getList($userId,$page,$limit);
				$hasNext = $follower->getHasNext($limit,$page,$followerCount);
			}

			$result = new FollowOrFollowerGetListResultVO($feedCount,$interestCount,$followCount,$followerCount,$followerList,$hasNext);
			return ServiceResult::withResult($result);
		});
	}
}