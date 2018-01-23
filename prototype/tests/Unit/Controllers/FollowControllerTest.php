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

    //101 -> 103
	//102 -> 103
	//102 -> 104
	//105 -> 102

    public function testSwitchFollowStatus(){
    	    $userId = 101;
    	    $userAuth = "AUTH_5a61c3b9d87ca"; //101 : AUTH_5a61c3b9d87ca //102 : AUTH_5a61c3b9d8a25 // 105 : AUTH_5a61c3b9d9480

			parent::prepareUserWithIdAndAuth($userId,$userAuth);

		    $httpMethod = HttpMethod::PUT;
		    $targetUserId = 105;
		    $isOn = 0;
		    $uri = "/user/follow/edit/{$targetUserId}/{$isOn}";
		    $content = $this->getJsonRequestContent($httpMethod, $uri);
			$this->printResponse($content);
            $this->assertEquals(StatusCode::SUCCESS,$content["code"]);

    }
	public function testGetFollowList(){
		$userId = 101;
		$ownerId = 102;
		$userAuth = "AUTH_5a61c3b9d87ca"; //101 : AUTH_5a61c3b9d87ca //102 : AUTH_5a61c3b9d8a25 // 105 : AUTH_5a61c3b9d9480

		parent::prepareUserWithIdAndAuth($userId,$userAuth);
		$httpMethod = HttpMethod::GET;
		$page = 1;
		$uri = "/user/follow/list/{$ownerId}?page={$page}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testGetFollowerList(){
		$userId = 101;
		$ownerId = 102;
		$userAuth = "AUTH_5a61c3b9d87ca"; //101 : AUTH_5a61c3b9d87ca //102 : AUTH_5a61c3b9d8a25 // 105 : AUTH_5a61c3b9d9480

		parent::prepareUserWithIdAndAuth($userId,$userAuth);
		$httpMethod = HttpMethod::GET;
		$page = 1;
		$uri = "/user/follower/list/{$ownerId}?page={$page}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}
}
