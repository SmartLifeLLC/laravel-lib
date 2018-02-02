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
        $janCode = (new ProductItemCategory())->getJanCode($oldProductId);
        $newProductID = (new JicfsProduct())->getProductId($janCode);
        return $newProductID;
    }
}