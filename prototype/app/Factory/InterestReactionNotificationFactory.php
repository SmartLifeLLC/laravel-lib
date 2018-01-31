<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/31
 * Time: 0:18
 */

namespace App\Factory;

use App\Constants\ContributionReactionType;
use App\Constants\DefaultValues;
use App\Constants\NotificationLogType;
use App\Constants\NotificationString;
use App\Lib\Util;
use App\Services\Tasks\NotificationTask\NotificationTask;

class InterestReactionNotificationFactory extends NotificationFactory
{

	private $reactionType;
	private $productName;

	static function getNotificationAllowColumn(): string
	{
		return "is_interest_notification_allowed";
	}


	public function __construct()
	{
		$this->reactionType = ContributionReactionType::INTEREST;
	}

	/**
	 * @param mixed $productName
	 * @return CommentNotificationFactory
	 */
	public function setProductName($productName):NotificationFactory
	{
		$this->productName = $productName;
		return $this;
	}




	function create(): NotificationTask
	{
		//Comment1
		//1.$message
		$name = Util::getStringWithMaxLength($this->userName,DefaultValues::MAX_LENGTH_NOTIFICATION_USERNAME);
		$productName = Util::getStringWithMaxLength($this->productName, DefaultValues::MAX_LENGTH_NOTIFICATION_PRODUCT);
		$message = NotificationString::getReaction($name,$productName,$this->reactionType);
		$notificationType = ($this->reactionType == ContributionReactionType::LIKE)?NotificationLogType::LIKE:NotificationLogType::INTEREST;
		return
			new NotificationTask($this->fromUserId,$this->targetUsers,$this->contributionId,$this->contributionCommentId,$notificationType,$message);
	}
}