<?php

namespace App\Models;

use App\Constants\DateTimeFormat;
use App\Constants\DefaultValues;
use App\Lib\Util;
use App\ValueObject\FacebookResponseVO;
use App\ValueObject\UserNotifyPropertiesVO;
use App\ValueObject\UserValidationVO;
use Illuminate\Database\Eloquent\Model;
use DB;
class User extends DBModel
{
    protected $table = 'users';
    public $timestamps = true;

	/**
	 * @param String $facebookId
	 * @param String $facebookToken
	 * @return UserValidationVO|null
	 */
    public function getUserValidationByFacebookId(String $facebookId, String $facebookToken):?UserValidationVO{
        $userData = $this->where("facebook_id",$facebookId)->select(["id","auth","limitation_level"])->first();
        if($userData == null) return null;
        $userData->facebook_token = $facebookToken;
        $userData->save();
        return (new UserValidationVO($userData->id,$userData->auth,$userData->limitation_level));
    }

    /**
     * @param FacebookResponseVO $fbData
     * @param String $auth
     * @return int
     */
    public function createUserDataAndGetId( FacebookResponseVO $fbData, String $auth){
        $userData =
            [
                'facebook_id' => $fbData->getFacebookId(),
                'mail_address' => $fbData->getEmail(),
                'first_name' => $fbData->getFirstName(),
                'last_name' => $fbData->getLastName(),
                'user_name' => $fbData->getUserName(),
                'description' => $fbData->getDescription(),
                'gender' => $fbData->getGender(),
                'birthday' => $fbData->getBirthday(),
                'worked_history' => "WORKED_HISTORY",
                'gender_published_flag' => DefaultValues::SETTING_USER_GENDER_PERMISSION,
                'birthday_published_flag' => DefaultValues::SETTING_USER_AGE_PERMISSION,
                'profile_image_id' => DefaultValues::CONTENTS_USER_PROFILE_IMG_ID,
                'cover_image_id' => DefaultValues::CONTENTS_USER_COVER_IMG_ID,
                'auth' => $auth,
                'created_at' => date(DateTimeFormat::General),
                'updated_at' => date(DateTimeFormat::General),
                'last_posted_at' => date(DateTimeFormat::General),
	            'facebook_token' => $fbData->getFacebookToken()
            ];


        $id = $this->insertGetId($userData);
        return $id;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserInfo($userId){
        return
            $this->where('users.id',$userId)
            ->select(
                'users.id as user_id',
                'facebook_id',
                'mail_address',
                'first_name',
                'last_name',
                'user_name',
                'users.description',
                'address',
                'gender',
                'birthday',
                'worked_history',
                'cover_images.s3_key as cover_image_s3_key',
                'profile_images.s3_key as profile_image_s3_key',
                'gender_published_flag',
                'birthday_published_flag',
                'users.created_at',
                'users.updated_at'
                )
            ->leftJoin('images as cover_images', 'users.cover_image_id', '=', 'cover_images.user_id')
            ->leftJoin('images as profile_images', 'users.profile_image_id', '=', 'profile_images.user_id')
            ->first();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getNotificationSettings($userId){
        return $this
                ->select(
                    'id',
                    'is_follow_notification_allowed',
                    'is_have_notification_allowed',
                    'is_like_notification_allowed',
                    'is_interest_notification_allowed',
                    'is_comment_notification_allowed'
                )
                ->where('users.id',$userId)
                ->first();
    }

    public function createAuthForUser($userId){
        $auth = Util::getUniqueAuth();
        $this->where('id',$userId)->update(['auth'=>$auth]);
        return $auth;
    }

    /**
     * @param $userId
     * @param int $profileId
     * @param int $coverId
     */
    public function updateUserImageData($userId,$profileId, $coverId){
        $data = [];
        if($profileId < 1 && $coverId < 1)
            return ;

        if($profileId > 0 ){
            $data['profile_image_id'] = $profileId;
        }

        if($coverId > 0){
            $data['cover_image_id'] = $coverId;
        }

        $this->where('id',$userId)->update($data);

    }

    /**
     * @param $userId
     * @return UserValidationVO|null
     */
    public function getUserValidationByUserId($userId):?UserValidationVO{
        $userData = $this->select(["id","auth","limitation_level"])->find($userId);
        if($userData == null) return null;
        return (new UserValidationVO($userData['id'],$userData->auth,$userData->limitation_level));
    }

	/**
	 * @return mixed
	 */
    public function getRandomUser(){
    	return $this->inRandomOrder()->first();
    }

	/**
	 * @param $userId
	 * @return mixed
	 */
    public function updateLastPostDate($userId){
    	return $this->where('id',$userId)->update(['last_posted_at'=>date(DateTimeFormat::General)]);
    }

	/**
	 * @param $userId
	 * @param $ownerId
	 * @return mixed
	 */
    public function getUserInfoForPage($userId, $ownerId){
    	return
		     $this
			     ->select(
			     	    'users.*',
			            'follows.id as my_follow_id',
			            'profile_images.s3_key as profile_image_s3_key',
			            'cover_images.s3_key as cover_image_s3_key'
				     )
			     ->where('users.id',$ownerId)
			     ->leftJoin('images as profile_images','profile_images.id','=','users.profile_image_id')
			     ->leftJoin('images as cover_images','cover_images.id','=','users.cover_image_id')
			     ->leftJoin('follows',function($join) use ($userId,$ownerId){
			     	$join->on('follows.user_id','=',DB::raw($userId));
			     	$join->on('follows.target_user_id','=',DB::raw($ownerId));})
//			     ->leftJoin('followers',function($join) use ($userId,$ownerId){
//				     $join->on('followers.user_id','=',DB::raw($userId));
//				     $join->on('followers.target_user_id','=',DB::raw($ownerId));})
		         ->first();

    }


	/**
	 * @param $userId
	 * @return mixed
	 */
    public function getFriendCount($userId){
        return
        $this
	        ->select(DB::raw('count(follows.id) as follow_count'),DB::raw('count(followers.id) as follower_count'))
	        ->where('users.id',$userId)
	        ->leftJoin('follows','follows.user_id','=',DB::raw($userId))
	        ->leftJoin('followers','followers.user_id','=',DB::raw($userId))
	        ->groupBy('follows.user_id','followers.user_id')
	        ->first();
    }

	/**
	 * @param $userId
	 * @param UserNotifyPropertiesVO $userNotifyPropertiesVO
	 */
    public function updateUserNotifyProperties($userId, UserNotifyPropertiesVO $userNotifyPropertiesVO){
    	$data = $userNotifyPropertiesVO->getData();
    	if(empty($data))
    		return;

    	return
		    $this->where('id',$userId)->update($data);
    }

    public function translateGetId($userData){
        return $this->insertGetId(
            [
                'id' => $userData['id'],
                'facebook_id' => $userData['facebookId'],
                'mail_address' => $userData['mailAddress'],
                'auth' => $userData['auth'],
                'first_name' => $userData['firstName'],
                'last_name' => $userData['lastName'],
                'user_name' => $userData['userName'],
                'birthday' => $userData['birthday'],
                'address' => $userData['address'],
                'gender' => $userData['gender'],
                'gender_published_flag' => $userData['genderPublishedFlag'],
                'birthday_published_flag' => $userData['birthdayPublishedFlag'],
                'worked_history' => $userData['workedHistory'],
                'profile_image_id' => $userData['profileImageId'],
                'cover_image_id' => $userData['coverImageId'],
                'is_follow_notification_allowed' => $userData['followNotification'],
                'is_have_notification_allowed' => $userData['haveNotification'],
                'is_like_notification_allowed' => $userData['likeNotification'],
                'is_interest_notification_allowed' => $userData['interestNotification'],
                'is_comment_notification_allowed' => $userData['commentNotification'],
                'description' => $userData['description'],
                'created_at' => $userData['created'],
                'last_posted_at' => $userData['updated'],
                'updated_at' => $userData['updated']

            ]
        );
    }

	/**
	 * @param $userId
	 * @return mixed
	 */
    public function getSimpleUserInfo($userId){
    	return $this->find($userId);
    }

	/**
	 * @param $userId
	 * @return mixed
	 */
	public function getUserFacebookInfo($userId){
    	return $this->find($userId)->select('facebook_id','facebook_token','birthday');
	}


//	/**
//	 * @param array $userIds
//	 * @param $notificationCheckColumn
//	 * @return array
//	 */
//	public function getNotificationTargetUsers(array $userIds,$notificationCheckColumn){
//		$users =
//			$this
//				->select('notification_token','users.id',"users.".$notificationCheckColumn)
//				->leftJoin('devices','devices.user_id','=','users.id')
//				->whereIn('users.id',$userIds)
//				->where('users.'.$notificationCheckColumn,1)
//				->get();
//
//		$data = [];
//		if(empty($users->count())) return $data;
//
//		foreach($userIds as $userId){
//			$data[$userId] = [];
//		}
//
//		foreach($users as $user){
//			$data[$user['id']][] = $user['notification_token'];
//		}
//
//		return $data;
//	}
}

