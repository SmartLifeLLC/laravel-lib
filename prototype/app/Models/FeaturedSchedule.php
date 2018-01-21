<?php

namespace App\Models;

use App\Constants\SystemConstants;
use App\Constants\URLs;
use Illuminate\Database\Eloquent\Model;
use DB;
class FeaturedSchedule extends DBModel
{
    protected $guarded = [];

    /**
     * @param $userId
     * @param $currentDate
     * @param $featuredScheduleTypeId
     * @return mixed
     */
    public function getFeaturedUsers($userId, $currentDate, $featuredScheduleTypeId){
        $imageHost = SystemConstants::getCdnHost();
        $query =
            "
                SELECT
                  users.id,users.user_name,users.description as introduction, concat(?,images.s3_key) as profile_image_url
                FROM featured_schedules
                  LEFT JOIN featured_users ON featured_schedules.id = featured_users.featured_schedule_id
                  LEFT JOIN users on featured_users.user_id = users.id
                  LEFT JOIN images on images.id = users.profile_image_id
                WHERE start_at < ?
                      AND end_at > ?
                      AND featured_schedule_type_id = ?
                      AND featured_users.user_id NOT IN (SELECT follows.target_user_id from follows where follows.user_id = ?  AND follows.is_on = true)
                      AND featured_users.user_id NOT IN (SELECT block_users.target_user_id from block_users where block_users.user_id = ?)
                      AND featured_users.user_id NOT IN (SELECT blocked_users.user_id from blocked_users where blocked_users.target_user_id= ?)
                      AND featured_users.user_id != ?
                      
                ORDER BY featured_users.weight DESC
                      ;
            ";
        $result = DB::select($query,[$imageHost,$currentDate,$currentDate,$featuredScheduleTypeId,$userId,$userId,$userId,$userId]);
        return $result;
    }
}