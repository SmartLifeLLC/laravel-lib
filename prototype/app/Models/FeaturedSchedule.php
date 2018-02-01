<?php

namespace App\Models;

use App\Constants\DateTimeFormat;
use App\Constants\FeaturedScheduleType;
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
    public function getFeaturedUsers($userId, $currentDate, $featuredScheduleTypeId, $limit = 30){
        $query =
            "
                SELECT
                  users.id,users.user_name,users.description, images.s3_key as profile_image_s3_key
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
                LIMIT {$limit}
                      ;
            ";
        $result = DB::select($query,[$currentDate,$currentDate,$featuredScheduleTypeId,$userId,$userId,$userId,$userId]);
        return $result;
    }

	/**
	 * @return mixed
	 */
    public function getFeaturedUserIds($type){
    	$currentDate = date(DateTimeFormat::General);
    	$result =
		    $this
		    ->select('featured_users.user_id')
		    ->leftJoin('featured_users','featured_users.featured_schedule_id','=','featured_schedules.id')
		    ->where('start_at','<',$currentDate)
		    ->where('end_at','>',$currentDate)
		    ->where('featured_schedule_type_id',$type)
		    ->get();

    	if(empty($result))
    		return [];
    	return $this->getArrayWithoutKey($result->toArray(),'user_id');
    }
}