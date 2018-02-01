<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:02
 */

namespace App\Services\Translate;

use App\Models\ContributionHaveReaction;
use App\Models\ContributionReactionCount;
use App\Models\Contribution;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousHaveReactionService extends BaseService
{
    /**
     * @param $userId
     * @param $contributionId
     * @param $created
     * @return ServiceResult
     */
    public function getData($userId, $contributionId, $created):ServiceResult{
        return $this->executeTasks(function() use($userId, $contributionId, $created) {
            $haveReactionId = (new ContributionHaveReaction())->translateGetId($userId, $contributionId, $created);
            $productId = (new Contribution())->getProductId($contributionId);
            (new ContributionReactionCount())->incrementCount($productId, $contributionId, 3);
            return ServiceResult::withResult($haveReactionId);
        },true);
    }
}