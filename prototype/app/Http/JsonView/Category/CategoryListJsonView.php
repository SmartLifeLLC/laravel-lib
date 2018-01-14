<?php
/**
 * class ListJsonView
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/14
 */
namespace App\Http\JsonView\Category;
use App\Http\JsonView\JsonResponseView;

class CategoryListJsonView extends JsonResponseView
{
    /**
     * @var see CategoryService::getList
     */
    protected $data;
    function createBody()
    {
        $this->body = $this->data;
    }
}