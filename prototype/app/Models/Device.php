<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 13:13
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
use Illuminate\Database\Eloquent\Model;

class Device extends DBModel
{

    protected $guarded = [];

    /**
     * @param $userId
     * @param $deviceUuid
     * @param $notificationToken
     * @param $deviceType
     * @return mixed
     */
    public function updateToken($userId, $deviceUuid, $notificationToken, $deviceType){
        $result = self::updateOrCreate(
            //Conditions
            [
                'user_id'=>$userId,
                'device_uuid'=>$deviceUuid],
            //Update value
            [
                'user_id'=>$userId,
                'device_uuid'=>$deviceUuid,
                'notification_token' => $notificationToken,
                'device_type' => $deviceType,
                'updated_at' => date(DateTimeFormat::General)
            ]
        );
        return $result;
    }


	/**
	 * @param array $userIds
	 * @return array
	 */
    public function getNotificationTargetUsers(array $userIds){
    	$result =
		    $this
			    ->select('notification_token','user_id')
		        ->whereIn('user_id',$userIds)
		        ->get();


    	$data = [];
    	foreach($userIds as $userId){
    		$data[$userId] = [];
	    }

	    foreach($result as $deviceToken){
			$data[$deviceToken['user_id']][] = $deviceToken;
	    }

	    return $data;
    }
}