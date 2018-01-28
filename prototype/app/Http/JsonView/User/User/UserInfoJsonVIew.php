<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/06
 * Time: 20:39
 */
namespace App\Http\JsonView\User\User;

use App\Constants\DateTimeFormat;
use App\Constants\SystemConstants;
use App\Constants\Gender;
use App\Constants\S3Buckets;
use App\Http\JsonView\JsonResponseView;
use App\Lib\Util;

class UserInfoJsonVIew extends JsonResponseView
{
    /**
     * See Models/User@getUser()
     * @var
     */
    protected $data;
    function createBody()
    {
        $user = $this->data;
        $this->body =
            [
                "user_id" => $user->user_id,
                "facebook_id"  => $user->facebook_id,
                "mail_address" => $user->mail_address,
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "user_name" => $user->user_name,
                "description" => $user->description,
                "address" => $user->address,
                "gender" => $this->getGenderString($user->gender,$user->gender_published_flag),
                "birthday" => $this->getBirthdayString($user->birthday,$user->birthday_published_flag),
                "worked_history" => $user->worked_history,
                "profile_image_url" => $this->getImageURLForS3Key($user->profile_image_s3_key),
                "cover_image_url" => $this->getImageURLForS3Key($user->cover_image_url),
                "gender_published_flag" => $user->gender_published_flag,
                "birthday_published_flag" => $user->birthday_published_flag,
                "created_at" => $user->created_at->format(DateTimeFormat::General),
                "updated_at" => $user->updated_at->format(DateTimeFormat::General)
            ];
    }
}