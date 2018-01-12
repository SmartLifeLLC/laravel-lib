<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/12
 * Time: 3:50
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * @param $name
     * @param $productBrandId
     * @param int $price
     * @param int $imageId
     * @return mixed
     */
    public function create($name,$productBrandId,$price = 0 ,$imageId = 0){
        $data = [
            'name' => $name,
            'product_brand_id' => $productBrandId,
            'price' => $price,
            'image_id' => $imageId
        ];
        return $this->insertGetId($data);
    }
}