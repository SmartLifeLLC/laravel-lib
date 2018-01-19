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

class BlockControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
	    parent::httpTestSetup();
        parent::prepareUser();
    }

    public function testOldBlockUser(){
        $httpMethod = HttpMethod::POST;
        $uri = '/api/block/user';
        $data = ['to'=>"49"];
        $content = $this->getJsonRequestContent($httpMethod,$uri,$data);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }

    public function testOldCancelBlock(){
        $httpMethod = HttpMethod::POST;
        $uri = '/api/block/cancel';
        $data = ['to'=>"49"];
        $content = $this->getJsonRequestContent($httpMethod,$uri,$data);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }

    public function testSwitchBlockStatus(){
        $httpMethod = HttpMethod::PUT;
        $targetUserId = 49;
        $isOn = "1";
        $uri = "/user/block/{$targetUserId}/{$isOn}";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }
}
