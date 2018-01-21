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
        foreach($this->data->getProducts() as $product){
            $product['image_url'] =
                (!empty($product['s3_key']))?SystemConstants::getCdnHost().$product['s3_key']:"";
            unset($product['s3_key']);
            $products[] = $product;
        }

        $this->body = [
            'count' =>$this->data->getCount(),
            'data' => $products
        ];
    }

}