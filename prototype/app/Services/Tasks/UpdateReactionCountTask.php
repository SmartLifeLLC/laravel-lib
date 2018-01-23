<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:07
 */

namespace App\Services\Tasks;


use App\Constants\ContributionReactionType;
use App\Lib\JSYService\ServiceTask;
use App\Models\Common\ContributionReactionInterface;
use App\Models\ContributionAllReaction;
use App\Models\ContributionHaveReaction;
use App\Models\ContributionInterestReaction;
use App\Models\ContributionLikeReaction;
use App\Models\ContributionReactionCount;
use App\Models\ContributionReactionNotificationDelivery;
use App\Services\NotificationService;

class UpdateReactionCountTask implements ServiceTask
{
	//Tables
	// contribution_reaction_counts
	// contribution_like_reactions
	// contribution_reaction_notification_deliveries
	// contribution_have_reactions
	// contribution_interest_reactions

	private $userId;
	private $contributionId;
	private $productId;
	private $contributionReactionType;
	private $isIncrease;

	/**
	 * UpdateReactionCountTask constructor.
	 * @param $userId
	 * @param $contributionId
	 * @param $productId
	 * @param $contributionReactionType
	 * @param $isIncrease
	 */
	public function __construct($userId, $contributionId, $productId, $contributionReactionType, $isIncrease)
	{
		$this->userId = $userId;
		$this->contributionId = $contributionId;
		$this->contributionReactionType = $contributionReactionType;
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
		switch ($this->contributionReactionType ){
			case ContributionReactionType::LIKE:{
				$contributionReactionModel = new ContributionLikeReaction();
				break;
			}
			case ContributionReactionType::INTEREST:{
				$contributionReactionModel = new ContributionInterestReaction();
				break;
			}
			case ContributionReactionType::HAVE:{
				$contributionReactionModel = new ContributionHaveReaction();
				break;
			}
			default :
				throw new \Exception("Failed to find reaction type for the ".$this->contributionReactionType);
		}

		if($this->isIncrease){
			$this->incrementCount($contributionReactionModel);
		}else{
			$this->decrementCount($contributionReactionModel);
		}

	}

	/**
	 * @param ContributionReactionInterface $contributionReactionModel
	 * @throws \Exception
	 */
	private function incrementCount(ContributionReactionInterface $contributionReactionModel){
		$contributionEntity = $contributionReactionModel->findReaction($this->userId,$this->contributionId);
		if(!empty($contributionEntity))
			throw new \Exception("User {$this->userId} already leave a reaction on {$this->contributionId} for reaction type : {$this->contributionReactionType}");

		$contributionReactionModel->createReaction($this->userId,$this->contributionId);
		(new ContributionAllReaction())->createReaction($this->userId,$this->contributionId,$this->contributionReactionType);
		(new ContributionReactionCount())->incrementCount($this->productId,$this->contributionId,$this->contributionReactionType);
		$isPreviousSent = (new ContributionReactionNotificationDelivery())->isPreviousSent($this->userId,$this->contributionId,$this->contributionReactionType);
		if(!$isPreviousSent){ (new SendNotificationTask())->run(); }
	}


	/**
	 * @param ContributionReactionInterface $contributionReactionModel
	 * @throws \Exception
	 */
	private function decrementCount(ContributionReactionInterface $contributionReactionModel){
		$reactionEntity = $contributionReactionModel->findReaction($this->userId,$this->contributionId);
		if(empty($reactionEntity))
			throw new \Exception("Failed to find reaction with user id {$this->userId} and contribution id  {$this->contributionId} for reaction type : {$this->contributionReactionType} ");
		$reactionEntity->delete();
		(new ContributionAllReaction())->deleteReaction($this->userId,$this->contributionId,$this->contributionReactionType);
		(new ContributionReactionCount())->decrementCount($this->productId,$this->contributionId,$this->contributionReactionType);
	}


	function getResult()
	{
		// TODO: Implement getResult() method.
		return null;
	}


}