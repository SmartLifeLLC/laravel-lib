<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/21
 * Time: 21:50
 */


namespace App\Services\Translate;


use App\Models\Contribution;
use App\Models\ContributionComment;
use App\Models\ContributionCommentCount;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousCommentService extends BaseService
{
    /**
     * @param int $userId
     * @param int $contributionId
     * @param string $content
     * @return ServiceResult
     */
    public function getData($userId, $contributionId, $content):ServiceResult{
        return $this->executeTasks(function() use($userId, $contributionId, $content) {
            $commentId = (new ContributionComment())->createGetId($userId, $contributionId, $content);
			(new ContributionCommentCount())->increaseCommentCount($contributionId);
            return ServiceResult::withResult($commentId);
        },true);
    }
}