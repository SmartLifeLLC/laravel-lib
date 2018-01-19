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
        $uri = "/user/info";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }

    public function testGetUserNotificationSettings(){
        $httpMethod = HttpMethod::GET;
        $uri = "/user/setting/notification/list";
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
}