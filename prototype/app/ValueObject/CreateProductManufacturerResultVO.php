<?php
/**
 * class CreateProductManufacturerResultVO
 * @package App\ValueObject
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/13
 */

namespace App\ValueObject;


class CreateProductManufacturerResultVO
{
    private $id;
    private $isCreated;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isCreated()
    {
        return $this->isCreated;
    }


    /**
     * CreateProductManufacturerResultVO constructor.
     * @param int $id
     * @param bool $isCreated
     */
    public function __construct(int $id,bool $isCreated)
    {
        $this->id = $id;
        $this->isCreated = $isCreated;
    }
}