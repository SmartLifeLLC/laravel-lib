<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/06
 * Time: 21:35
 */

namespace Tests\Unit\Controllers;
use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use App\Models\CurrentUser;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
	    parent::httpTestSetup();
        parent::prepareUser();
    }

    public function testGetInfo(){
        $httpMethod = HttpMethod::GET;
        $uri = "/user/info/get";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }

	public function testEdit(){
		$httpMethod = HttpMethod::PUT;
		$uri = "/user/info/edit";
		$data =
			[
				'birthday_published_flag'=>0,
				'gender_published_flag'=>0,
				'gender'=>0,
				'birthday'=>"2017-01-01 10:10:10",
				'description'=>'NEW description 2 ',
				'user_name'=>"NEW USER NAME 2  ",
				'mail_address' => "jung@smt-life.biz",
				'delete_cover_image' => 1,
				'delete_profile_image' => 1

			];
		$content = $this->getJsonRequestContent($httpMethod,$uri,$data);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

    public function testGetUserNotificationSettings(){
        $httpMethod = HttpMethod::GET;
        $uri = "/user/notification-setting/list";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }

    public function testGetUserNotificationSettingsOld(){
        $httpMethod = HttpMethod::GET;
        $uri = "/api/user/setting/show/1";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }



    public function testGetPageInfo(){
    	//$this->prepareUserWithIdAndAuth(50,"AUTH_5a61c35655e8e");
	    $httpMethod = HttpMethod::GET;
	    $uri = "/user/page/get/48";
	    $content = $this->getJsonRequestContent($httpMethod,$uri);
	    if(!isset($content['code']))  var_dump($content);
	    $this->printResponse($content);
	    $this->printSQLLog();
	    $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }

}