<?php
/**
 * class AuthContorlloerTest
 * @package Tests\Unit\Controllers
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */

namespace Tests\Unit\Controllers;

use App\Constants\HeaderKeys;
use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
        parent::httpTestSetup();
        parent::prepareFacebookToken();
        parent::prepareUser();
    }

    public function testGetIdAndAuth(){
	    $httpMethod = HttpMethod::POST;
	    $uri = '/user/auth/'.$this->facebookId;
	    $content = $this->getJsonRequestContent($httpMethod,$uri);
	    $this->printResponse($content);
	    $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }
}
