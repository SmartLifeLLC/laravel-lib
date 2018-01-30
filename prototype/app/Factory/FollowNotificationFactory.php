<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/29
 * Time: 16:23
 */

namespace App\Factory;


use App\Constants\DefaultValues;
use App\Constants\NotificationLogType;
use App\Constants\NotificationString;
use App\Lib\Util;
use App\Services\Tasks\NotificationTask\NotificationTask;

class FollowNotificationFactory extends NotificationFactory
{
	static function getNotificationAllowColumn():string
	{
		return "is_follow_notification_allowed";
	}


	function create(): NotificationTask
	{
		//Comment
		//1.$message
		$name = Util::getStringWithMaxLength($this->userName,DefaultValues::MAX_LENGTH_NOTIFICATION_USERNAME);
		$message = NotificationString::getFollow($name);
		return
			new NotificationTask($this->fromUserId,$this->targetUsers,$this->contributionId,$this->contributionCommentId,NotificationLogType::FOLLOW,$message);
	}
}