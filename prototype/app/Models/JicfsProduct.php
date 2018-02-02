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
    public $timestamps = false;

    /**
     * @param $janCode
     * @param $voucherName
     * @return mixed
     */
    public function getProduct($janCode, $voucherName){
        return $this->where('jan_code',$janCode)->where('voucher_name',$voucherName)->first();
    }

    /**
     * @param array $janCode
     * @param $name
     * @param $productId
     * @param $jicfsCategoryId
     * @param $productCategoryId
     * @param $productManufacturerId
     * @param $displayUnit
     * @param $voucherName
     * @param $totalQuantity
     * @param $quantityType
     * @param $releaseDate
     * @return $this|Model|int
     */
    public function createGetId(
        $janCode,
        $name,
        $productId,
        $jicfsCategoryId,
        $productCategoryId,
        $productManufacturerId,
        $displayUnit,
        $voucherName,
        $totalQuantity,
        $quantityType,
        $releaseDate
        ){

        $data = [
            'jan_code' => $janCode,
            'name'  => $name,
            'product_id'=> $productId,
            'jicfs_category_id' => $jicfsCategoryId,
            'product_category_id' => $productCategoryId,
            'jicfs_manufacturer_id' => $productManufacturerId,
            'display_unit' => $displayUnit,
            'voucher_name' => $voucherName,
            'total_quantity' => $totalQuantity,
            'quantity_type' => $quantityType
        ];

        if($releaseDate != null) $data['release_date'] = $releaseDate;
        return $this->insertGetId($data);
    }

    public function getProductId($janCode){
        return $this
            ->where('jan_code', $janCode)
            ->select('product_id')
            ->first();
    }
}