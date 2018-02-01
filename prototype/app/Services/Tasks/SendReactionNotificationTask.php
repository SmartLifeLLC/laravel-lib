<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:30
 */

namespace App\Services\Tasks;


use App\Constants\ContributionReactionType;
use App\Factory\InterestReactionNotificationFactory;
use App\Factory\LikeReactionNotificationFactory;
use App\Lib\JSYService\ServiceTask;
use App\Models\Device;
use App\Models\NotificationLog;

class SendReactionNotificationTask implements ServiceTask
{

	/**
	 * @var int
	 */
	private $fromUserId;

	/**
	 * @var string
	 */
	private $fromUserName;

	/**
	 * @var int
	 */
	private $targetUserId;

	/**
	 * @var int
	 */
	private $contributionId;

	/**
	 * @var string
	 */
	private $productName;

	/**
	 * @var
	 */
	private $reactionTypeId;

	/**
	 * @var InterestReactionNotificationFactory | LikeReactionNotificationFactory
	 */
	private $factory;

	/**
	 * @var string
	 */
	private $isAllowedColumn;

	/**
	 * @var
	 */
	private $result;

	/**
	 * SendReactionNotificationTask constructor.
	 * @param int $fromUserId
	 * @param string $fromUserName
	 * @param int $targetUserId
	 * @param int $contributionId
	 * @param string $productName
	 * @param $reactionTypeId
	 */
	public function __construct(int $fromUserId,string $fromUserName,int $targetUserId,int $contributionId,string $productName, $reactionTypeId)
	{
		$this->fromUserId = $fromUserId;
		$this->targetUserId = $targetUserId;
		$this->fromUserName = $fromUserName;
		$this->productName = $productName;
		$this->contributionId = $contributionId;
		$this->reactionTypeId = $reactionTypeId;
		if($reactionTypeId == ContributionReactionType::LIKE){
			$this->factory = new LikeReactionNotificationFactory();
			$this->isAllowedColumn = LikeReactionNotificationFactory::getNotificationAllowColumn();
		}else{
			$this->factory = new InterestReactionNotificationFactory();
			$this->isAllowedColumn = InterestReactionNotificationFactory::getNotificationAllowColumn();
		}
	}


	function run()
	{
		$notificationTargetUsers = (new Device())->getNotificationTargetUsers([$this->targetUserId],$this->isAllowedColumn);
		$notification =
			$this->factory
					->setProductName($this->productName)
					->setUserName($this->fromUserName)
					->setFromUserId($this->fromUserId)
					->setTargetUsers($notificationTargetUsers)
					->create();
		$notification->run();
		//Send
		$this->result = $notification->getResult();
		//insert
		(new NotificationLog())->saveData($notification->getSaveData());
	}

	function getResult()
	{
		return $this->result;
	}

}