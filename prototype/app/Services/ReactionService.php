<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 13:54
 */

namespace App\Services;


use App\Constants\DefaultValues;
use App\Constants\FeedReactionType;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Models\BlockUser;
use App\Models\Feed;
use App\Models\FeedAllReaction;
use App\Models\FeedHaveReaction;
use App\Models\FeedInterestReaction;
use App\Models\FeedLikeReaction;
use App\Models\FeedReactionCount;
use App\Services\Tasks\UpdateReactionCountTask;
use App\ValueObject\ReactionGetLIstResultVO;
use function foo\func;

class ReactionService extends BaseService
{

	/**
	 * @param $userId
	 * @param $feedId
	 * @param $reactionType
	 * @param $isIncrease
	 * @return ServiceResult
	 */
	public function updateReaction($userId,$feedId,$reactionType,$isIncrease):ServiceResult{
		return $this->executeTasks(function()use ($userId,$feedId,$reactionType,$isIncrease){
			$feedEntity = (new Feed())->find($feedId);
			if(empty($feedEntity))
				return ServiceResult::withError(StatusCode::FAILED_TO_FIND_FEED,"Can't find feed id for ({$feedId})");

			$isBlocked = (new BlockUser())->isBlockStatus($userId,$feedEntity['user_id']);
			if($isBlocked)
				return ServiceResult::withError(StatusCode::BLOCK_STATUS_WITH_TARGET_USER,"user {$userId} and target user {$feedEntity['user_id']} is block status.");

			(new UpdateReactionCountTask($userId,$feedId,$feedEntity['product_id'],$reactionType,$isIncrease))->run();

			return ServiceResult::withResult(true);
		},true);
	}


	/**
	 * @param $userId
	 * @param $feedId
	 * @param $reactionType
	 * @param $page
	 * @param int $limit
	 * @return ServiceResult
	 */
	public function getList($userId, $feedId, $reactionType, $page, $limit =  DefaultValues::QUERY_DEFAULT_LIMIT):ServiceResult{
		return $this->executeTasks(function ()use($userId,$feedId,$reactionType,$page, $limit){
			$counts = (new FeedReactionCount())->getCountsForFeed($feedId);
			if(empty($counts)) {
				$reactionList = new ReactionGetLIstResultVO([],[],false);
			}else {

				// TODO: Implement run() method.
				//create model for reaction type
				switch ($reactionType) {
					case FeedReactionType::LIKE:
						{
							$feedReactionModel = new FeedLikeReaction();
							$totalCount = $counts->like_reaction_count;
							break;
						}
					case FeedReactionType::INTEREST:
						{
							$feedReactionModel = new FeedInterestReaction();
							$totalCount = $counts->interest_reaction_count;
							break;
						}
					case FeedReactionType::HAVE:
						{
							$feedReactionModel = new FeedHaveReaction();
							$totalCount = $counts->have_reaction_count;
							break;
						}
					default :
						$feedReactionModel = new FeedAllReaction();
						$totalCount = $counts->total_reaction_count;
						break;
				}
				if($totalCount == 0){
					$reactionList = new ReactionGetLIstResultVO($counts,[],false);
				}else{
					$reactionEntities = $feedReactionModel->getList($userId,$feedId,$page,$limit);
					$hasNext = $feedReactionModel->getHasNext($limit,$page,$totalCount);
					$reactionList = new ReactionGetLIstResultVO($counts,$reactionEntities,$hasNext);
				}
			}
			return ServiceResult::withResult($reactionList);
		});
	}

}