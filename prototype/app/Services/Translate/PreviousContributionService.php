<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:01
 */

namespace App\Services\Translate;

use App\Models\Contribution;
use App\Models\NegativeProductContribution;
use App\Models\PositiveProductContribution;
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
            if($feeling = 'positive')
                (new PositiveProductContribution())->createGetId($contributionId, $productId);
            else if($feeling == 'negative')
                (new NegativeProductContribution())->createGetId($contributionId, $productId);
            return ServiceResult::withResult($contributionId);
        },true);
    }
}