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

    public function testGetList(){
        $httpMethod = HttpMethod::GET;
        $keyword = base64_encode("インペ");
        $uri = "/product/list?keyword={$keyword}&category=1&sort=feed_count_desc,release_date_desc";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);

    }
}