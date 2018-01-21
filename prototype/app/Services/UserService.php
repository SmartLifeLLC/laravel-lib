<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/06
 * Time: 20:10
 */

namespace App\Services;


use App\Constants\DateTimeFormat;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Models\BlockUser;
use App\Models\Feed;
use App\Models\FeedAllReaction;
use App\Models\FeedReactionCount;
use App\Models\User;
use App\ValueObject\PageInfoResultVO;
use App\ValueObject\UserEditVO;
use App\ValueObject\UserNotifyPropertiesVO;

class UserService extends BaseService
{
    /**
     * @param $userId
     * @return ServiceResult
     */
    public function getUserInfo($userId):ServiceResult{
        return $this->executeTasks(
            function() use ($userId) : ServiceResult{
            $user = (new User())->getUserInfo($userId);
            $serviceResult = (empty($user))?
                ServiceResult::withError(StatusCode::UNKNOWN_USER_ID,"user id : {$userId} is not found"):
                ServiceResult::withResult($user);
            return $serviceResult;
        });
    }

    /**
     * @param $userId
     * @return ServiceResult
     */
    public function getNotificationSettings($userId):ServiceResult{
        return $this->executeTasks(function() use ($userId) : ServiceResult{
            $user = (new User())->getNotificationSettings($userId);
            $serviceResult = (empty($user))?
                ServiceResult::withError(StatusCode::UNKNOWN_USER_ID,"user id : {$userId} is not found"):
                ServiceResult::withResult($user);
            return $serviceResult;
        });
    }

	/**
	 * @param $userId
	 * @param UserEditVO $userEditVO
	 * @return ServiceResult
	 */
    public function edit($userId,UserEditVO $userEditVO):ServiceResult{
    	return $this->executeTasks(function() use($userId,$userEditVO):ServiceResult{
			$saveData = $userEditVO->getSaveData();
			if(!empty($saveData)) {
				(new User())->where('id', $userId)->update($saveData);
			}
			return ServiceResult::withResult($userId);
	    },true);
    }

	/**
	 * @param $userId
	 * @param $ownerId
	 * @return ServiceResult
	 */
    public function getPageInfo($userId,$ownerId){
		return $this->executeTasks(function() use ($userId,$ownerId){
			$userModel = new User();
			$userInfoForPage = $userModel->getUserInfoForPage($userId,$ownerId);
			$friendsCount = $userModel->getFriendCount($ownerId);
			$allReactionCount = (new FeedAllReaction())->getReactionCountsForUser($ownerId);
			$feedCount = (new Feed())->getCountForUser($userId);
			$result = new PageInfoResultVO($userInfoForPage,$feedCount,$friendsCount,$allReactionCount);
			return ServiceResult::withResult($result);
		});
    }


	/**
	 * @param $userId
	 * @param UserNotifyPropertiesVO $userNotifyPropertiesVO
	 * @return ServiceResult
	 */
    public function updateNotifyProperties($userId, UserNotifyPropertiesVO $userNotifyPropertiesVO):ServiceResult{
    	return $this->executeTasks(function() use ($userId,$userNotifyPropertiesVO){
		    (new User())->updateUserNotifyProperties($userId,$userNotifyPropertiesVO);
		    return ServiceResult::withResult(true);
	    },true);
    }
}