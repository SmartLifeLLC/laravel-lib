<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/09
 * Time: 20:44
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class JicfsCategory extends DBModel
{
    /**
     * @param $categoryCode
     * @param $productCategoryId
     * @param $largeSectionName
     * @param $mediumSectionName
     * @param $smallSectionName
     */
    function createCategory($categoryCode,$productCategoryId,$largeSectionName,$mediumSectionName,$smallSectionName){
        self::insert(
                [
                    'category_code'=>$categoryCode,
                    'product_category_id'=>$productCategoryId,
                    'large_section_name'=>$largeSectionName,
                    'medium_section_name'=>$mediumSectionName,
                    'small_section_name'=>$smallSectionName,
                ]
        );
    }

    /**
     * @param $code
     * @return mixed
     */
    public function getCategoryInfo($code){
        return $this->where('category_code',$code)->first();
    }
}