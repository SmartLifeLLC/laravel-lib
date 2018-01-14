<?php
/**
 * class CreateProductTask
 * @package App\Services\Tasks
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/13
 */

namespace App\Services\Tasks;


use App\Lib\JSYService\ServiceTask;
use App\Lib\Logger;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductsProductCategory;
use App\ValueObject\CreateProductResultVO;

class CreateGetProductIdTask implements ServiceTask
{

    /**
     * @var CreateProductResultVO
     */
    private $result;

    private $productName;

    private $productManufacturerId;

    private $price;

    private $releaseDate;

    private $productCategoryId;

    public function __construct($productName,$productManufacturerId,$price,$releaseDate,$productCategoryId)
    {
        $this->productName = $productName;
        $this->productManufacturerId = $productManufacturerId;
        $this->price = $price;
        $this->releaseDate = $releaseDate;
        $this->productCategoryId = $productCategoryId;
    }


    function run()
    {
        $productModel = new Product();
        //productのユニーク性は名前で確認(jancodeでは判断できない)
        $product = $productModel->getProductByName($this->productName);
        if($product !== null){

            $this->result = new CreateProductResultVO(false,$product['id']);
            return;
        }

        //Create product
        $productId = (new Product())->createGetId($this->productName,$this->productManufacturerId,$this->price,$this->releaseDate);

        //Create category
        (new ProductsProductCategory())->createGetId($productId,$this->productCategoryId);

        //update count on category
        //対象 : カテゴリ IDのものと そのidを子で持っている全てのカテゴリ
        (new ProductCategory())->increaseProductCount($this->productCategoryId);
        $this->result = new CreateProductResultVO(true,$productId);
    }

    /**
     * @return CreateProductResultVO
     */
    function getResult()
    {
        return $this->result;
    }
}