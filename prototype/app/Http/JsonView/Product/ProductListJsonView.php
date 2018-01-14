<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/15
 * Time: 1:49
 */

namespace App\Http\JsonView\Product;


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
        $this->body = [
            'count' =>$this->data->getCount(),
            'data' => $this->data->getData()
        ];
    }

}