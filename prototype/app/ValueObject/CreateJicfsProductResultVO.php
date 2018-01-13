<?php
/**
 * class CreateProductResultVO
 * @package App\ValueObject
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/13
 */

namespace App\ValueObject;


class CreateJicfsProductResultVO
{
    private $isJicfsProductCreated = false;
    private $isProductCreated = false;
    private $jicfsProductId = 0;
    private $productId = 0;

    /**
     * @return bool
     */
    public function isJicfsProductCreated(): bool
    {
        return $this->isJicfsProductCreated;
    }

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
    public function getJicfsProductId(): int
    {
        return $this->jicfsProductId;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }
    public function __construct($isJicfsProductCreated,$isProductCreated,$jicfsProductId,$productId)
    {
        $this->isJicfsProductCreated = $isJicfsProductCreated;
        $this->isProductCreated = $isProductCreated;
        $this->jicfsProductId =  $jicfsProductId;
        $this->productId = $productId;
    }

}