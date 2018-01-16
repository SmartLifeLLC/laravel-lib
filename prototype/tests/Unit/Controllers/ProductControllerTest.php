<?php
/**
 * class ProductControllerTest
 * @package Tests\Unit\Controllers
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/14
 */

namespace Tests\Unit\Controllers;


use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        parent::prepareAuth();
    }

//    public function testGetListByKeyword(){
//        $httpMethod = HttpMethod::GET;
//        $keyword = base64_encode("インペ");
//        $uri = "/product/list?keyword={$keyword}&page=45";
//        $content = $this->getJsonRequestContent($httpMethod,$uri);
//        $this->printResponse($content);
//        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
//    }
//
//    public function testGetListByCategoryId(){
//        $httpMethod = HttpMethod::GET;
//        $uri = "/product/list?category=2&page=41";
//        $content = $this->getJsonRequestContent($httpMethod,$uri);
//        $this->printResponse($content);
//        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
//    }


    public function testGetListByJanCode(){
        $httpMethod = HttpMethod::GET;
        $uri = "/product/list?jan_code=0000100990942";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        var_dump($content);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }


}