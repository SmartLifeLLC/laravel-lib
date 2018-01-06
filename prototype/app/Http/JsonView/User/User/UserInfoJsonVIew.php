<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/06
 * Time: 20:39
 */
namespace App\Http\JsonView\User\User;

use App\Constants\ConfigConstants;
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
                "gender" => Gender::getString($user->gender),
                "birthday" => $user->birthday,
                "worked_history" => $user->worked_history,
                "profile_image_url" => Util::getS3ImageFullPath($user->profile_image_url),
                "cover_image_url" => Util::getS3ImageFullPath($user->cover_image_url),
                "gender_published_flag" => $user->gender_published_flag,
                "birthday_published_flag" => $user->birthday_published_flag,
                "created_at" => $user->created_at->format('Y-m-d H:i:s'),
                "updated_at" => $user->updated_at->format('Y-m-d H:i:s')
            ];
    }
}