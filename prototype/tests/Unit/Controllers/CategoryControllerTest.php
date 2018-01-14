<?php
/**
 * class CategoryControllerTest
 * @package Tests\Unit\Controllers
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/14
 */

namespace Tests\Unit\Controllers;


use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        parent::prepareAuth();
    }

    public function testGetList(){
        $httpMethod = HttpMethod::GET;
        $uri = '/category/list/866';
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);

    }
}