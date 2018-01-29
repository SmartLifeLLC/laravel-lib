<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 13:54
 */

namespace App\Services;


use App\Constants\DefaultValues;
use App\Constants\ContributionReactionType;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Models\BlockUser;
use App\Models\Contribution;
use App\Models\ContributionAllReaction;
use App\Models\ContributionHaveReaction;
use App\Models\ContributionInterestReaction;
use App\Models\ContributionLikeReaction;
use App\Models\ContributionReactionCount;
use App\Services\Tasks\UpdateReactionCountTask;
use App\ValueObject\ReactionGetListResultVO;
use function foo\func;

class ReactionService extends BaseService
{

	/**
	 * @param $userId
	 * @param $contributionId
	 * @param $reactionType
	 * @param $isIncrease
	 * @return ServiceResult
	 */
	public function updateReaction($userId, $contributionId, $reactionType, $isIncrease):ServiceResult{
		return $this->executeTasks(function()use ($userId,$contributionId,$reactionType,$isIncrease){
			$contributionEntity = (new Contribution())->find($contributionId);
			if(empty($contributionEntity))
				return ServiceResult::withError(StatusCode::FAILED_TO_FIND_CONTRIBUTION,"Can't find contribution id for ({$contributionId})");

			$isBlocked = (new BlockUser())->isBlockStatus($userId,$contributionEntity['user_id']);
			if($isBlocked)
				return ServiceResult::withError(StatusCode::BLOCK_STATUS_WITH_TARGET_USER,"user {$userId} and target user {$contributionEntity['user_id']} is block status.");

			(new UpdateReactionCountTask($userId,$contributionId,$contributionEntity['product_id'],$reactionType,$isIncrease))->run();

			return ServiceResult::withResult(true);
		},true);
	}


	/**
	 * @param $userId
	 * @param $contributionId
	 * @param $reactionType
	 * @param $page
	 * @param int $limit
	 * @return ServiceResult
	 */
	public function getList($userId, $contributionId, $reactionType, $page, $limit =  DefaultValues::QUERY_DEFAULT_LIMIT):ServiceResult{
		return $this->executeTasks(function ()use($userId,$contributionId,$reactionType,$page, $limit){
			$blockUsers = (new BlockUser())->getBlockAndBlockedUserIds($userId);
			$counts = (new ContributionReactionCount())->getCountsForContribution($contributionId,$blockUsers);
			if(empty($counts)) {
				$reactionList = new ReactionGetListResultVO([],[],false);
			}else {

				// TODO: Implement run() method.
				//create model for reaction type
				switch ($reactionType) {
					case ContributionReactionType::LIKE:
						{
							$contributionReactionModel = new ContributionLikeReaction();
							$totalCount = $counts->like_reaction_count;
							break;
						}
					case ContributionReactionType::INTEREST:
						{
							$contributionReactionModel = new ContributionInterestReaction();
							$totalCount = $counts->interest_reaction_count;
							break;
						}
					case ContributionReactionType::HAVE:
						{
							$contributionReactionModel = new ContributionHaveReaction();
							$totalCount = $counts->have_reaction_count;
							break;
						}
					default :
						$contributionReactionModel = new ContributionAllReaction();
						$totalCount = $counts->total_reaction_count;
						break;
				}
				if($totalCount == 0){
					$reactionList = new ReactionGetListResultVO($counts,[],false);
				}else{
					$reactionEntities = $contributionReactionModel->getList($userId,$contributionId,$blockUsers,$page,$limit);
					$hasNext = $contributionReactionModel->getHasNext($limit,$page,$totalCount);
					$reactionList = new ReactionGetListResultVO($counts,$reactionEntities,$hasNext);
				}
			}
			return ServiceResult::withResult($reactionList);
		});
	}

}