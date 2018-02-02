<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 20:41
 */

namespace App\Services;

use App\Constants\DateTimeFormat;
use App\Constants\DefaultValues;
use App\Constants\FeaturedScheduleType;
use App\Constants\StatusCode;
use App\Constants\URLs;
use App\Lib\JSYService\NormalServiceManager;
use App\Lib\JSYService\ServiceResult;
use App\Models\FeaturedSchedule;
use App\Models\User;
use App\Services\Tasks\GetFacebookFriendListTask;
use App\ValueObject\GetFeaturedUsersForFeedVO;

class FeaturedService extends BaseService
{
    /**
     * @param int $userId
     * @return ServiceResult
     */
    public function getFeaturedUsersForInitStart(int $userId):ServiceResult{
        return $this->executeTasks($this->_getFeaturedUsersForInitStartTask($userId));
    }

    /**
     * @param int $userId
     * @return \Closure
     */
    private function _getFeaturedUsersForInitStartTask(int $userId):\Closure{
        return function () use ($userId){
            $result = (new FeaturedSchedule())->getFeaturedUsers($userId,date(DateTimeFormat::General),FeaturedScheduleType::INIT_START);
            $serviceResult = ServiceResult::withResult($result);
            return $serviceResult;
        };
    }

	/**
	 * @param int $userId
	 * @return ServiceResult
	 */
    public function getFeaturedUsersForFeedTmp(int $userId):ServiceResult{
    	return $this->executeTasks(function () use ($userId){
    		$userModel = (new User());
    		$result = $userModel->inRandomOrder()->limit(30)->get();
    		return ServiceResult::withResult($result);
	    });
    }

	/**
	 * @param int $userId
	 * @return ServiceResult
	 */
    public function getFeaturedUsersForFeed(int $userId):ServiceResult{
		return $this->executeTasks(function() use ($userId){

			//Get all facebook friend list
			$userModel = new User();
			$userInfo = $userModel->getSimpleUserInfo($userId);
			$facebookId = $userInfo['facebook_id'];
			$facebookToken = $userInfo['facebook_token'];
			$birthday = $userInfo['birthday'];
			$gender = $userInfo['gender'];


			$getFacebookFriendListTask = new GetFacebookFriendListTask($facebookId,$facebookToken);
			$getFacebookFriendListTask->run();
			$allFacebookFriendIds = $getFacebookFriendListTask->getResult();
			if(isset($allFacebookFriendIds['error']))
				ServiceResult::withError(StatusCode::FACEBOOK_FRIEND_API_ERROR,json_encode($allFacebookFriendIds));

			$limit = DefaultValues::FEED_TOTAL_FEATURED_USER_NUM;
			$featuredUsersFromFacebookFriend = $userModel->getFeaturedUserListFromFacebookIdsWithCount($userId,$gender,$birthday,$allFacebookFriendIds,$limit,1);

			$limitForPickupUser = $limit - $featuredUsersFromFacebookFriend->getCount();
			$pickupUsers = [];
			if($limit > 0 ){
				$pickupUsers = (new FeaturedSchedule())->getFeaturedUsers($userId,date(DateTimeFormat::General),FeaturedScheduleType::FEED,$limitForPickupUser);
			}
			$featuredUsers = new GetFeaturedUsersForFeedVO($featuredUsersFromFacebookFriend->getData(),$pickupUsers);
			return ServiceResult::withResult($featuredUsers);
		});
    }


	/**
	 * @param int $userId
	 * @param $page
	 * @param $limit
	 * @return ServiceResult
	 */
    public function getFeaturedUsersForFacebook(int $userId,$page,$limit):ServiceResult{
    	return $this->executeTasks(function () use ($userId,$page,$limit){
		    //Get all facebook friend list
		    $userModel = new User();
		    $userInfo = $userModel->getSimpleUserInfo($userId);
		    $facebookId = $userInfo['facebook_id'];
		    $facebookToken = $userInfo['facebook_token'];
		    $birthday = $userInfo['birthday'];
		    $gender = $userInfo['gender'];


		    $getFacebookFriendListTask = new GetFacebookFriendListTask($facebookId,$facebookToken);
		    $getFacebookFriendListTask->run();
		    $allFacebookFriendIds = $getFacebookFriendListTask->getResult();
		    if(isset($allFacebookFriendIds['error']))
			    ServiceResult::withError(StatusCode::FACEBOOK_FRIEND_API_ERROR,json_encode($allFacebookFriendIds));

		    $featuredUsersFromFacebookFriend = $userModel->getFeaturedUserListFromFacebookIdsWithCount($userId,$gender,$birthday,$allFacebookFriendIds,$limit,$page);
			return ServiceResult::withResult($featuredUsersFromFacebookFriend);

	    });
    }


	/**
	 * @param int $userId
	 * @return ServiceResult
	 */
    public function getFeaturedUsersForPickup(int $userId):ServiceResult{
		return $this->executeTasks(function() use ($userId){
			$pickupUsers = (new FeaturedSchedule())->getFeaturedUsers(
				$userId,
				date(DateTimeFormat::General),
				FeaturedScheduleType::FEED,DefaultValues::PICKUP_TOTAL_FEATURED_USER_NUM);
			return ServiceResult::withResult($pickupUsers);

		});
    }


}