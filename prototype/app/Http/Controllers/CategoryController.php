<?php
/**
 * class CategoryController
 * @package App\Http\Controllers
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/14
 */

namespace App\Http\Controllers;


use App\Http\JsonView\Category\CategoryListJsonView;
use App\Services\CategoryService;

class CategoryController extends Controller
{

    /**
     * @param int $ancestorId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList($ancestorId = 0){
        $serviceResult = (new CategoryService())->getList($ancestorId);
        $jsonView = new CategoryListJsonView($serviceResult);
        return $this->responseJson($jsonView);
    }
}