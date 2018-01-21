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
use App\Models\User;
use App\ValueObject\UserEditVO;

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


}