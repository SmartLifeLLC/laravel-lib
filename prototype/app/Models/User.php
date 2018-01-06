<?php

namespace App\Models;

use App\Constants\DateTimeFormat;
use App\Constants\DefaultValues;
use App\Lib\Util;
use App\ValueObject\FacebookResponseVO;
use App\ValueObject\UserAuthVO;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    public $timestamps = true;

    /**
     * @param String $facebookId
     * @return UserAuthVO|null
     */
    public function getAuthByFacebookId(String $facebookId):?UserAuthVO{
        $userData = $this->where("facebook_id",$facebookId)->select(["id","auth"])->first();
        if($userData == null) return null;
        return (new UserAuthVO())->setUserId($userData->id)->setAuth($userData->auth);
    }

    /**
     * @param FacebookResponseVO $fbData
     * @param String $auth
     * @return int
     */
    public function createUserDataAndGetId(
        FacebookResponseVO $fbData,
        String $auth
    ){
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
                'last_posted_at' => date(DateTimeFormat::General)
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
                'cover_images.s3_key as cover_image_url',
                'profile_images.s3_key as profile_image_url',
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
     * @return UserAuthVO|null
     */
    public function getAuthByUserId($userId):?UserAuthVO{
        $userData = $this->where("id",$userId)->select(["id","auth"])->first();
        if($userData == null) return null;
        return (new UserAuthVO())->setUserId($userData->id)->setAuth($userData->auth);
    }
}
