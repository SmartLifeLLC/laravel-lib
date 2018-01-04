<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 12:52
 */

namespace Tests\Unit\Controllers;

use App\Constants\HeaderKeys;
use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use Tests\TestCase;

class FollowControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        parent::prepareAuth();
    }

//    public function testOldBlockUser(){
//        $httpMethod = HttpMethod::POST;
//        $uri = '/api/follow/user';
//        $data = ['to'=>"49"];
//        $content = $this->getJsonRequestContent($httpMethod,$uri,$data);
//        var_dump($content);
//        $this->printResponse($content);
//        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
//    }
//
//    public function testOldCancelBlock(){
//        $httpMethod = HttpMethod::POST;
//        $uri = '/api/follow/cancel';
//        $data = ['to'=>"49"];
//        $content = $this->getJsonRequestContent($httpMethod,$uri,$data);
//        $this->printResponse($content);
//        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
//    }
//
    public function testSwitchBlockStatus(){
        $httpMethod = HttpMethod::PUT;
        $targetUserId = 49;
        $isOn = "1";
        $uri = "/user/follow/{$targetUserId}/{$isOn}";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);

    }
}
