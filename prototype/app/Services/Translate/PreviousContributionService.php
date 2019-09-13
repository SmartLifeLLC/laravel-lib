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
use App\Services\Tasks\UpdateContributionCountTask;
use DB;

class PreviousContributionService extends BaseService
{
    /**
     * @param $id
     * @param $userId
     * @param $oldProductId
     * @param $feeling
     * @param $images
     * @param $content
     * @param $created
     * @param $updated
     * @return ServiceResult
     */
    public function getData($id, $userId, $oldProductId, $feeling, $images, $content, $created, $updated):ServiceResult{
        return $this->executeTasks(function() use($id, $userId, $oldProductId, $feeling, $images, $content, $created, $updated) {
            $productId = (new ChangeProductIdService())->getNewProductId($oldProductId);

            $contributionId = (new Contribution())->translateGetId($id, $userId, $productId, $feeling, $images, $content, $created, $updated);

            //Increase count.
            $isCreated = true;
            (new UpdateContributionCountTask($contributionId,$productId,$isCreated,$feeling))->run();

            return ServiceResult::withResult($contributionId);
        },true);
    }
}