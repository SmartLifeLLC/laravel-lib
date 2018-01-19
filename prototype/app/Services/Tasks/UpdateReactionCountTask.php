<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:07
 */

namespace App\Services\Tasks;


use App\Constants\FeedReactionType;
use App\Lib\JSYService\ServiceTask;
use App\Models\Common\FeedReactionInterface;
use App\Models\FeedAllReaction;
use App\Models\FeedHaveReaction;
use App\Models\FeedInterestReaction;
use App\Models\FeedLikeReaction;
use App\Models\FeedReactionCount;
use App\Models\FeedReactionNotificationDelivery;
use App\Services\NotificationService;

class UpdateReactionCountTask implements ServiceTask
{
	//Tables
	// feed_reaction_counts
	// feed_like_reactions
	// feed_reaction_notification_deliveries
	// feed_have_reactions
	// feed_interest_reactions

	private $userId;
	private $feedId;
	private $productId;
	private $feedReactionType;
	private $isIncrease;

	/**
	 * UpdateReactionCountTask constructor.
	 * @param $userId
	 * @param $feedId
	 * @param $productId
	 * @param $feedReactionType
	 * @param $isIncrease
	 */
	public function __construct($userId, $feedId, $productId, $feedReactionType, $isIncrease)
	{
		$this->userId = $userId;
		$this->feedId = $feedId;
		$this->feedReactionType = $feedReactionType;
		$this->productId = $productId;
		$this->isIncrease = $isIncrease;
	}

	/**
	 * @throws \Exception
	 */
	function run()
	{
		// TODO: Implement run() method.
		//create model for reaction type
		switch ($this->feedReactionType ){
			case FeedReactionType::LIKE:{
				$feedReactionModel = new FeedLikeReaction();
				break;
			}
			case FeedReactionType::INTEREST:{
				$feedReactionModel = new FeedInterestReaction();
				break;
			}
			case FeedReactionType::HAVE:{
				$feedReactionModel = new FeedHaveReaction();
				break;
			}
			default :
				throw new \Exception("Failed to find reaction type for the ".$this->feedReactionType);
		}

		if($this->isIncrease){
			$this->incrementCount($feedReactionModel);
		}else{
			$this->decrementCount($feedReactionModel);
		}

	}

	/**
	 * @param FeedReactionInterface $feedReactionModel
	 * @throws \Exception
	 */
	private function incrementCount(FeedReactionInterface $feedReactionModel){
		$feedEntity = $feedReactionModel->findReaction($this->userId,$this->feedId);
		if(!empty($feedEntity))
			throw new \Exception("User {$this->userId} already leave a reaction on {$this->feedId} for reaction type : {$this->feedReactionType}");

		$feedReactionModel->createReaction($this->userId,$this->feedId);
		(new FeedAllReaction())->createReaction($this->userId,$this->feedId,$this->feedReactionType);
		(new FeedReactionCount())->incrementCount($this->productId,$this->feedId,$this->feedReactionType);
		$isPreviousSent = (new FeedReactionNotificationDelivery())->isPreviousSent($this->userId,$this->feedId,$this->feedReactionType);
		if(!$isPreviousSent){ (new SendNotificationTask())->run(); }
	}


	/**
	 * @param FeedReactionInterface $feedReactionModel
	 * @throws \Exception
	 */
	private function decrementCount(FeedReactionInterface $feedReactionModel){
		$reactionEntity = $feedReactionModel->findReaction($this->userId,$this->feedId);
		if(empty($reactionEntity))
			throw new \Exception("Failed to find reaction with user id {$this->userId} and feed id  {$this->feedId} for reaction type : {$this->feedReactionType} ");
		$reactionEntity->delete();
		(new FeedAllReaction())->deleteReaction($this->userId,$this->feedId,$this->feedReactionType);
		(new FeedReactionCount())->decrementCount($this->productId,$this->feedId,$this->feedReactionType);
	}


	function getResult()
	{
		// TODO: Implement getResult() method.
		return null;
	}


}