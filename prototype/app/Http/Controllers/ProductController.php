<?php
/**
 * class ProductController
 * @package App\Http\Controllers
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/14
 */

namespace App\Http\Controllers;


use App\Constants\DefaultValues;
use App\Constants\StatusCode;
use App\Http\JsonView\JsonResponseErrorView;
use App\Http\JsonView\Product\ProductListJsonView;
use App\Lib\Util;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getList(Request $request){

        $keyword = $request->get('keyword');
        if($keyword != null){ $keyword = base64_decode($keyword);}
        $limit = Util::getValueForKeyOnGetRequest($request,'limit',DefaultValues::QUERY_DEFAULT_LIMIT);
        $page = Util::getValueForKeyOnGetRequest($request,'page',DefaultValues::QUERY_DEFAULT_PAGE);
        $categoryId = $request->get('category');

        /**
         * Parameter error
         */
        if($keyword == null && $categoryId == null){
            $jsonResponseView = new JsonResponseErrorView(
                StatusCode::REQUEST_PARAMETER_ERROR,
                "At least 1 keyword or category id is necessary for getting product list"
            );
            return $this->responseJson($jsonResponseView);
        }

        if($keyword == null){
            $serviceResult = (new ProductService())->getProductListByCategory($categoryId,$limit,$page);
        }else{
            $serviceResult = (new ProductService())->getProductListByKeyword($keyword,$categoryId,$limit,$page);
        }

        return $this->responseJson(new ProductListJsonView($serviceResult));
    }
}