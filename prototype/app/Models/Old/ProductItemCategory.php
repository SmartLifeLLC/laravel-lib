<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 2:38
 */

namespace App\Models\Old;


class ProductItemCategory extends OldModel
{
    protected $table = 'product_item_category';

    /**
     * @param $productID
     * @return mixed
     */
    public function getJanCode($productID){
        return $this
            ->where('product_item_id', $productID)
            ->select('jan_code')
            ->first();
    }
}