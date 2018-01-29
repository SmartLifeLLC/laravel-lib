<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:01
 */

namespace App\Services\Translate;

use App\Models\Contribution;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousContributionService extends BaseService
{
    /**
     * @param $userId
     * @param $productId
     * @param $feeling
     * @param $images
     * @param $content
     * @param $created
     * @return ServiceResult
     */
    public function getData($userId, $productId, $feeling, $images, $content, $created):ServiceResult{
        return $this->executeTasks(function() use($userId, $productId, $feeling, $images, $content, $created) {
            $contributionId = (new Contribution())->translateGetId($userId, $productId, $feeling, $images, $content, $created);
            return ServiceResult::withResult($contributionId);
        },true);
    }
}