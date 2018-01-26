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
    	$this->prepareUserWithIdAndAuth(48,1);
        $httpMethod = HttpMethod::PUT;
        $targetUserId = 50;
        $isOn = "1";
        $uri = "/user/block/edit/{$targetUserId}/{$isOn}";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }

    public function testList(){
	    $httpMethod = HttpMethod::GET;
	    $userId = 6155;
	    $auth = "AUTH_5a61c3bd4fbdd";
	    $this->prepareUserWithIdAndAuth($userId,$auth);
	    $uri = "/user/block/list";
	    $content = $this->getJsonRequestContent($httpMethod,$uri);
	    $this->printResponse($content);
	    $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }
}
