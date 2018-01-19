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
	    parent::httpTestSetup();
	    parent::prepareUser();
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
    public function testSwitchFollowStatus(){
    	for($i=0;$i<5;$i++) {
		    parent::prepareUser();
		    $httpMethod = HttpMethod::PUT;
		    $targetUserId = rand(1, 1000);
		    $isOn = "1";
		    $uri = "/user/follow/{$targetUserId}/{$isOn}";
		    $content = $this->getJsonRequestContent($httpMethod, $uri);
		    var_dump($content);
		    //$this->printResponse($content);
	    }
        //$this->assertEquals(StatusCode::SUCCESS,$content["code"]);

    }
	public function testGetFollowList(){
		$httpMethod = HttpMethod::GET;
		$page = 1;
		$uri = "/user/follow/list/?page={$page}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testGetFollowerList(){
		$httpMethod = HttpMethod::GET;
		$page = 1;
		$uri = "/user/follower/list/?page={$page}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}
}
