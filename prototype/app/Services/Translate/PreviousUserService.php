<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/30
 * Time: 0:03
 */

namespace App\Services\Translate;

use App\Models\User;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousUserService extends BaseService
{
    /**
     * @param $userData
     * @return ServiceResult
     */
    public function getData($userData): ServiceResult
    {
        return $this->executeTasks(function () use ($userData) {
            $userId = (new User())->translateGetId($userData);
            return ServiceResult::withResult($userId);
        }, true);
    }
}