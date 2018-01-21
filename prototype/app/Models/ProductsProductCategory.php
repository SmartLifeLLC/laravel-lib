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

	/**
	 * @param $productId
	 * @return array
	 */
    public function getLeafProductCategoryIds($productId){
    	$productProductCategories = $this->where('product_id',$productId)->get();
    	$ids = [];
    	foreach ($productProductCategories as $productCategory){
			$ids[] = $productCategory->product_category_id;
	    }
	    return $ids;
    }

    public function getProductCategories($productId){
    	return $this
		    ->where('product_id',$productId)
		    ->leftJoin('product_categories','product_categories.id','=','product_category_id')
		    ->get();

    }
}