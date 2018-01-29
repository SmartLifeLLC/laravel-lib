<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:02
 */

namespace App\Services\Translate;

use App\Models\ContributionLikeReaction;
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
            return ServiceResult::withResult($likeReactionId);
        },true);
    }
}