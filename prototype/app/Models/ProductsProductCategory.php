<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/12
 * Time: 4:12
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductsProductCategory extends DBModel
{
    /**
     * @param $productId
     * @param $productCategoryId
     * @return mixed
     */
    public function createGetId($productId,$productCategoryId){
        $data = [
            'product_id' => $productId,
            'product_category_id'=>$productCategoryId
        ];
        return $this->insertGetId($data);
    }


}