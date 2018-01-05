<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 20:41
 */

namespace App\Services;

use App\Constants\DateTimeFormat;
use App\Constants\FeaturedScheduleType;
use App\Lib\JSYService\NormalServiceManager;
use App\Lib\JSYService\ServiceResult;
use App\Models\FeaturedSchedule;

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
            foreach ($result as &$data){
                $data->profile_image_url = (empty($data->profile_image_url))?"":$data->profile_image_url;
                $data->is_following = false;
                $data->is_followerd = false;
            }
            $serviceResult = ServiceResult::withResult($result);
            return $serviceResult;
        };
    }
}