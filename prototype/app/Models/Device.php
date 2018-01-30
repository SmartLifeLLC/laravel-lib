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
	 * @param $notificationCheckColumn
	 * @return array
	 */
    public function getNotificationTargetUsers(array $userIds,$notificationCheckColumn){
    	$users =
		    $this
			    ->select('notification_token','user_id',"users.".$notificationCheckColumn)
			    ->leftJoin('users','users.id','=','devices.user_id')
		        ->whereIn('user_id',$userIds)
			    ->where('users.'.$notificationCheckColumn,1)
		        ->get();

    	$data = [];
	    if(empty($users->count())) return $data;

    	foreach($userIds as $userId){
    		$data[$userId] = [];
	    }

	    foreach($users as $user){
			$data[$user['user_id']][] = $user['notification_token'];
	    }

	    return $data;
    }
}