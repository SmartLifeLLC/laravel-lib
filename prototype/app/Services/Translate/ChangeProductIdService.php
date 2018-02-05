<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 2:27
 */

namespace App\Services\Translate;


use App\Models\JicfsProduct;
use App\Models\Old\ProductItemCategory;
use App\Services\BaseService;

class ChangeProductIdService extends BaseService
{
    public function getNewProductId($oldProductId){
        $janCodeData = (new ProductItemCategory())->getJanCode($oldProductId);
        $productIdData = (new JicfsProduct())->getProductId($janCodeData->jan_code);
        $newProductID = $productIdData->product_id;
        return $newProductID;
    }
}