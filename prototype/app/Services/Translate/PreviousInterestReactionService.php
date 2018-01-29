<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:03
 */

namespace App\Services\Translate;

use App\Models\ContributionInterestReaction;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousInterestReactionService extends BaseService
{
    /**
     * @param $userId
     * @param $contributionId
     * @param $created
     * @return ServiceResult
     */
    public function getData($userId, $contributionId, $created):ServiceResult{
        return $this->executeTasks(function() use($userId, $contributionId, $created) {
            $interestReactionId = (new ContributionInterestReaction())->translateGetId($userId, $contributionId, $created);
            return ServiceResult::withResult($interestReactionId);
        },true);
    }
}