<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/15
 * Time: 1:49
 */

namespace App\Http\JsonView\Product;


use App\Constants\SystemConstants;
use App\Http\JsonView\JsonResponseView;
use App\ValueObject\ProductAndCountDataVO;

class ProductListJsonView extends JsonResponseView
{


    /**
     * @var ProductAndCountDataVO
     */
    protected $data;


    function createBody()
    {
        $products = [];

	    $categories = $this->data->getProductsCategories();
	    $productsCategories = [];
	    foreach($categories as $category){
		    $categoryItem = $this->getWelFormedCategory(
			    $category['product_category_id'],
			    $category['name'],
			    $category['unique_name'],
			    (int) $category['product_count']
		    );
		    $productsCategories[$category['product_id']][$category['product_category_id']] = $categoryItem;
	    }


        foreach($this->data->getProducts() as $product){
	        $welFormedProduct =
		        $this->getProductHashArray(
			        $product['id'],
			        $product['display_name'],
			        $product['price'],
			        $product['contribution_count'],
			        $product['positive_count'],
			        $product['negative_count'],
			        array_values($productsCategories[$product['id']]),
			        $product['product_image_s3_key'],
			        $shops=[]
		        );
            $products[] = $welFormedProduct;
        }

        $this->body = [
            'count' =>$this->data->getCount(),
            'data' => $products
        ];
    }

}