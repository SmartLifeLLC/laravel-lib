<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:02
 */

namespace App\Services\Translate;

use App\Constants\ContributionReactionType;
use App\Constants\StatusCode;
use App\Models\BlockUser;
use App\Models\ContributionLikeReaction;
use App\Models\ContributionReactionCount;
use App\Models\Contribution;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousLikeReactionService extends BaseService
{
    /**
     * @param $userId
     * @param $contributionId
     * @param $created
     * @return ServiceResult
     */
    public function getData($userId, $contributionId, $created):ServiceResult{
        return $this->executeTasks(function() use($userId, $contributionId, $created) {
            $likeReactionId = (new ContributionLikeReaction())->translateGetId($userId, $contributionId, $created);

            $contributionEntity = (new Contribution())->getContributionWithProductName($contributionId);
            if(empty($contributionEntity))
                return ServiceResult::withError(StatusCode::FAILED_TO_FIND_CONTRIBUTION,"Can't find contribution id for ({$contributionId})");

            $isBlocked = (new BlockUser())->isBlockStatus($userId,$contributionEntity['user_id']);
            if($isBlocked)
                return ServiceResult::withError(StatusCode::BLOCK_STATUS_WITH_TARGET_USER,"user {$userId} and target user {$contributionEntity['user_id']} is block status.");

            $productId = (new Contribution())->getProductId($contributionId);
            (new ContributionReactionCount())->incrementCount($productId, $contributionId, ContributionReactionType::LIKE);

            return ServiceResult::withResult($likeReactionId);
        },true);
    }
}