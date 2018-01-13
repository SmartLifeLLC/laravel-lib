<?php
/**
 * class CreateProductResultVO
 * @package App\ValueObject
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/13
 */

namespace App\ValueObject;


class CreateProductResultVO
{



    private $isProductCreated = false;
    private $productId = 0;

    /**
     * @return bool
     */
    public function isProductCreated(): bool
    {
        return $this->isProductCreated;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }
    public function __construct($isProductCreated,$productId)
    {
        $this->isProductCreated = $isProductCreated;
        $this->productId = $productId;
    }
}