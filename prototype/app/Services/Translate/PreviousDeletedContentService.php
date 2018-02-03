<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/31
 * Time: 21:49
 */

namespace App\Services\Translate;

use App\Models\DeletedContent;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousDeletedContentService extends BaseService
{
    /**
     * @param $id
     * @param $targetId
     * @param $targetTable
     * @param $userId
     * @param $content
     * @param $relatedContent
     * @param $created
     * @return ServiceResult
     */
    public function getData($id, $targetId, $targetTable, $userId, $content, $relatedContent, $created):ServiceResult{
        return $this->executeTasks(function() use($id, $targetId, $targetTable, $userId, $content, $relatedContent, $created) {
            $contributionId = (new DeletedContent())->translateGetId($id, $targetId, $targetTable, $userId, $content, $relatedContent, $created);
            return ServiceResult::withResult($contributionId);
        },true);
    }
}