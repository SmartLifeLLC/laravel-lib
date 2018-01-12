<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/12
 * Time: 2:21
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class JicfsProduct extends Model
{
    public function getProduct($janCode,$name){
        return $this->where('jan_code',$janCode)->where('name',$name)->first();
    }

    /**
     * @param $janCode
     * @param $name
     * @param $jicfsCategoryId
     * @param $productCategoryId
     * @param $productBrandId
     * @return mixed
     */
    public function create($janCode,$name,$productId,$jicfsCategoryId,$productCategoryId,$productBrandId){
        $data = [
            'jan_code' => $janCode,
            'name'  => $name,
            'product_id'=> $productId,
            'jicfs_category_id' => $jicfsCategoryId,
            'product_category_id' => $productCategoryId,
            'product_brand_id' => $productBrandId
        ];
        return $this->insertGetId($data);
    }


}