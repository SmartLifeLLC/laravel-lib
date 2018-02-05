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
use App\Models\ProductCategoryContributionCount;
use App\Models\ProductContributionCount;
use App\Models\ProductsProductCategory;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
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
            $productCategoryIds = (new ProductsProductCategory())->getLeafProductCategoryIds($productId);

            $contributionId = (new Contribution())->translateGetId($id, $userId, $productId, $feeling, $images, $content, $created, $updated);

            if($feeling = 'positive') {
                (new PositiveProductContribution())->createGetId($contributionId, $productId);
                (new ProductContributionCount())->increasePositiveCount($productId);
                (new ProductCategoryContributionCount())->increasePositiveCount($productCategoryIds);
            }
            else if($feeling == 'negative') {
                (new NegativeProductContribution())->createGetId($contributionId, $productId);
                (new ProductContributionCount())->increaseNegativeCount($productId);
                (new ProductCategoryContributionCount())->increaseNegativeCount($productCategoryIds);
            }
            return ServiceResult::withResult($contributionId);
        },true);
    }
}