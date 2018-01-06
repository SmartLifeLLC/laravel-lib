<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/06
 * Time: 20:37
 */

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\JsonView\User\User\NotificationSettingJsonView;
use App\Http\JsonView\User\User\UserInfoJsonVIew;
use App\Models\CurrentUser;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo(){
        $userId = CurrentUser::shared()->getUserId();
        $serviceResult = (new UserService())->getUserInfo($userId);
        $jsonView = new UserInfoJsonVIew($serviceResult);
        return $this->responseJson($jsonView);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationSettings(){
        $userId = CurrentUser::shared()->getUserId();
        $serviceResult = (new UserService())->getNotificationSettings($userId);
        $jsonView = new NotificationSettingJsonView($serviceResult);
        return $this->responseJson($jsonView);
    }
}