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
	private $getFacebookFriendListTask = null;

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
	 * @param $facebookId
	 * @param $facebookToken
	 * @return mixed
	 */
    private function getAllFacebookFriendList($facebookId,$facebookToken){
	    $facebookFriends = [];

	    $fbResult = $this->getFacebookFriendList($facebookId,$facebookToken);
	    if(isset($fbResult['error'])){
		    return $fbResult;
	    }else{
		    foreach($fbResult['data'] as $friend){
			    $facebookFriends[] = $friend['id'];
		    }

		    if(isset($fbResult['paging'])&&isset($fbResult['paging']['cursors'])&&isset($fbResult['paging']['cursors']['after'])){
			    $after = $fbResult['paging']['cursors']['after'];
		    }
	    }

	    while (!empty($after)){
		    $fbResult = $this->getFacebookFriendList($facebookId,$facebookToken,$after);
		    foreach($fbResult['data'] as $friend){
			    $facebookFriends[] = $friend['id'];
		    }
		    if(isset($fbResult['paging'])&&isset($fbResult['paging']['cursors'])&&isset($fbResult['paging']['cursors']['after'])){
			    $after = $fbResult['paging']['cursors']['after'];
		    }else{
			    $after = null;
		    }
	    }
    }


	/**
	 * @param $facebookId
	 * @param $facebookToken
	 * @param null $after
	 * @return mixed
	 */
    private function getFacebookFriendList($facebookId, $facebookToken, $after = null){
		if($this->getFacebookFriendListTask == null)
			$this->getFacebookFriendListTask = new GetFacebookFriendListTask($facebookId,$facebookToken,$after);
		$this->getFacebookFriendListTask->run();
		return $this->getFacebookFriendListTask->getResult();
    }
}