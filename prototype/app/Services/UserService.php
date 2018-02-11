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
use App\Models\Contribution;
use App\Models\ContributionAllReaction;
use App\Models\ContributionReactionCount;
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

			//check block status
			if($userId != $ownerId){
				$isBlocked = (new BlockUser())->isBlockStatus($userId,$ownerId);
				if($isBlocked){
					return ServiceResult::withError(StatusCode::BLOCK_STATUS_WITH_TARGET_USER,"user {$userId} and {$ownerId} are in block status");
				}
			}

			$userInfoForPage = $userModel->getUserInfoForPage($userId,$ownerId);
			$allReactionCount = (new ContributionAllReaction())->getReactionCountsForUser($ownerId);
			$contributionCount = (new Contribution())->getCountForUser($ownerId);
			$result = new PageInfoResultVO($userInfoForPage,$contributionCount,$allReactionCount);
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