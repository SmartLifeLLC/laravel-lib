<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/30
 * Time: 10:18
 */

namespace App\Services\Tasks\NotificationTask\Factory;


use App\Constants\DefaultValues;
use App\Constants\NotificationLogType;
use App\Constants\NotificationString;
use App\Lib\Util;
use App\Services\Tasks\NotificationTask\Notification;

class CommentRelatedNotificationFactory extends NotificationFactory
{

	private $productName;

	/**
	 * @param mixed $productName
	 * @return CommentNotificationFactory
	 */
	public function setProductName($productName):NotificationFactory
	{
		$this->productName = $productName;
		return $this;
	}

	function create(): Notification
	{
		//Comment
		//1.$message
		$name = Util::getStringWithMaxLength($this->userName,DefaultValues::MAX_LENGTH_NOTIFICATION_USERNAME);
		$productName = Util::getStringWithMaxLength($this->productName, DefaultValues::MAX_LENGTH_NOTIFICATION_PRODUCT);
		$message = NotificationString::getCommentRelated($name,$productName);
		return
			new Notification($this->fromUserId,$this->targetUsers,$this->contributionId,$this->contributionCommentId,NotificationLogType::COMMENT_RELATED,$message);
	}
}