<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 22:07
 */

namespace Tests\Unit\Controllers;
use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use App\Models\CurrentUser;
use Tests\TestCase;

class NotificationLogControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        parent::prepareAuth();
    }

    public function testGetNotificationLogs(){
        $httpMethod = HttpMethod::GET;
        $uri = "/user/notificationLogs/101";
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }
}