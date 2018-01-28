<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:59
 */

namespace App\Services\Translate;

use App\Models\ContributionAllReaction;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousAllReactionService extends BaseService
{
    /**
     * @param $userId
     * @param $contributionId
     * @param $reactionType
     * @param $created
     * @return ServiceResult
     */
    public function getData($userId, $contributionId, $reactionType, $created):ServiceResult{
        return $this->executeTasks(function() use($userId, $contributionId, $reactionType, $created) {
            $reactionId = (new ContributionAllReaction())->translateGetId($userId, $contributionId, $reactionType, $created);
            return ServiceResult::withResult($reactionId);
        },true);
    }
}