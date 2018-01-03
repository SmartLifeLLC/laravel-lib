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
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private $facebookId;
    private $facebookToken;

    public function setUp()
    {
        parent::setUp();
        $request = new Request(

        );
        $this->facebookId = "10214655735416121";

        //この値は時限があるので注意
        $this->facebookToken = "EAAEFt83nGV4BACn1kY66nefV22tYZByweasyt9grC50vUP9TSkmh2v0k2Qqzm22nBvz0mIdnIWUgx1QM3pzjLAZCT5mlRAOUFKSNAZCCtMUw3NU5pDRKLtOFuRb2HOvbU6ngFWmxsVpfVhecCnmaOtDvHPpnZBN9aVglHnm0ZBIBdFAtB82X6GJqkBaisKq0ZBlbNV2U15MJr3AKdj9RrCVL99u7GrQsEkm4OM4cWYKQZDZD";
    }


    public function testGetIdAndAuth(){
        $response = $this->json(
            HttpMethod::GET,
            '/users/auth/'.$this->facebookId,
            [],
            [
                HeaderKeys::FB_TOKEN=>$this->facebookToken,
                HeaderKeys::REACT_VERSION=>1
            ]

        );
        $content = $response->getContent();
        var_dump($content);

    }
}
