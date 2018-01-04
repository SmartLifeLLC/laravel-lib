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
        parent::prepareFacebookToken();
    }


    public function testGetIdAndAuth(){
        $response = $this->json(
            HttpMethod::GET,
            '/user/auth/'.$this->facebookId,
            [],
            [
                HeaderKeys::FB_TOKEN=>$this->facebookToken,
                HeaderKeys::REACT_VERSION=>1
            ]
        );
        $content = $response->getContent();
        $result = json_decode($content,true);
        $this->assertEquals(StatusCode::SUCCESS,$result["code"]);

    }
}
