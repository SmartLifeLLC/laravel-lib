<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/21
 * Time: 21:50
 */


namespace App\Services\Translate;

use App\Models\ContributionComment;
use App\Models\ContributionCommentCount;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousCommentService extends BaseService
{
    /**
     * @param $userId
     * @param $contributionId
     * @param $content
     * @param $created
     * @return ServiceResult
     */
    public function getData($userId, $contributionId, $content, $created, $updated):ServiceResult{
        return $this->executeTasks(function() use($userId, $contributionId, $content, $created, $updated) {
            $commentId = (new ContributionComment())->translateGetId($userId, $contributionId, $content, $created, $updated);

			(new ContributionCommentCount())->increaseCommentCount($contributionId);

            return ServiceResult::withResult($commentId);
        },true);
    }
}