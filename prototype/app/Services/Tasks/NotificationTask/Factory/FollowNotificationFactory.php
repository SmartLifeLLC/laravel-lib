<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/29
 * Time: 16:23
 */

namespace App\Services\Tasks\NotificationTask\Factory;


use App\Constants\DefaultValues;
use App\Constants\NotificationLogType;
use App\Constants\NotificationString;
use App\Lib\Util;
use App\Services\Tasks\NotificationTask\Notification;

class FollowNotificationFactory extends NotificationFactory
{

	function create(): Notification
	{
		//Comment
		//1.$message
		$name = Util::getStringWithMaxLength($this->userName,DefaultValues::MAX_LENGTH_NOTIFICATION_USERNAME);
		$message = NotificationString::getFollow($name);
		return
			new Notification($this->fromUserId,$this->targetUsers,$this->contributionId,$this->contributionCommentId,NotificationLogType::FOLLOW,$message);
	}
}