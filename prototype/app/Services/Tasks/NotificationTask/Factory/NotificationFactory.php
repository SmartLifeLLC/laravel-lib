<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/29
 * Time: 16:14
 */

namespace App\Services\Tasks\NotificationTask\Factory;
use App\Services\Tasks\NotificationTask\Notification;
abstract class NotificationFactory
{

	protected $targetUsers;

	protected $fromUserId;

	protected $contributionId = 0 ;

	protected $contributionCommentId = 0;

	protected $userName;

	//protected $notificationLogTypeId;


	/**
	 * @param string $userName
	 * @return NotificationFactory
	 */
	public function setUserName(string $userName): NotificationFactory
	{
		$this->userName = $userName;
		return $this;
	}
//
//	/**
//	 * @param int $notificationLogTypeId
//	 * @return NotificationFactory
//	 */
//	public function setNotificationLogTypeId(int $notificationLogTypeId): NotificationFactory
//	{
//		$this->notificationLogTypeId = $notificationLogTypeId;
//		return $this;
//	}


	/**
	 * @param array $targetUsers
	 * @return NotificationFactory
	 */
	public function setTargetUsers(array $targetUsers):NotificationFactory{
		$this->targetUsers = $targetUsers;
		return $this;
	}

	/**
	 * @param $fromUserId
	 * @return NotificationFactory
	 */
	public function setFromUserId($fromUserId):NotificationFactory{
		$this->fromUserId = $fromUserId;
		return $this;
	}

	/**
	 * @param $contributionId
	 * @return NotificationFactory
	 */
	public function setContributionId($contributionId):NotificationFactory{
		$this->contributionId = $contributionId;
		return $this;
	}

	/**
	 * @param $contributionCommentId
	 * @return NotificationFactory
	 */
	public function setContributionCommentId($contributionCommentId):NotificationFactory{
		$this->contributionCommentId = $contributionCommentId;
		return $this;
	}

	abstract function create():Notification;





}