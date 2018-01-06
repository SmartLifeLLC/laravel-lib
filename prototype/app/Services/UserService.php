<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/06
 * Time: 20:10
 */

namespace App\Services;


use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Models\User;

class UserService extends BaseService
{
    /**
     * @param $userId
     * @return ServiceResult
     */
    public function getUserInfo($userId):ServiceResult{
        return $this->executeTasks(function() use ($userId) : ServiceResult{
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
}