<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 22:19
 */

namespace Tests\Unit\Controllers;

use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use Tests\TestCase;

class FeaturedUsersControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
	    parent::httpTestSetup();
        parent::prepareUser();
    }

    public function testGetFeatureUserListOld(){
        $httpMethod = HttpMethod::POST;
        $uri = "/api/recommend_users";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }

    public function testGetFeatureUserList(){
        $httpMethod = HttpMethod::GET;
        $uri = "/featured/users/onInit";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        var_dump($content);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }
}