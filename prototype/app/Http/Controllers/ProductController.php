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
use App\Constants\PostParametersValidationRule;
use App\Constants\StatusCode;
use App\Http\JsonView\JsonResponseErrorView;
use App\Http\JsonView\Product\ProductListJsonView;
use App\Lib\Util;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {

        $keyword = $request->get('keyword');
        $limit = Util::getValueForKeyOnGetRequest($request, 'limit', DefaultValues::QUERY_DEFAULT_LIMIT);
        $page = Util::getValueForKeyOnGetRequest($request, 'page', DefaultValues::QUERY_DEFAULT_PAGE);
        $categoryId = $request->get('category');
        $janCode = $request->get('jan_code');

        //Keyword Search
        if ($keyword != null){
            $serviceResult = (new ProductService())->getProductListByKeyword($keyword, $categoryId, $limit, $page);

        //Jan Code Search
        }else if ($janCode != null){
            $serviceResult = (new ProductService())->getProductListByJanCode($janCode);

        //Category Search
        }else if (!empty($categoryId)){
            $serviceResult = (new ProductService())->getProductListByCategory($categoryId,$limit,$page);

        //Error return
        }else{
            $jsonResponseView = new JsonResponseErrorView(
                StatusCode::REQUEST_PARAMETER_ERROR,
                "At least 1 keyword or category id or jan code is necessary for getting product list"
            );
            return $this->responseJson($jsonResponseView);
        }

        return $this->responseJson(new ProductListJsonView($serviceResult));
    }
}