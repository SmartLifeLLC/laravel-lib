<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 22:07
 */

namespace Tests\Unit\Controllers;
use App\Constants\HttpMethod;
use App\Constants\ListType;
use App\Constants\StatusCode;
use App\Models\CurrentUser;
use Tests\TestCase;

class NotificationLogControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
	    parent::httpTestSetup();
        parent::prepareUser();
    }

    public function testGetNotificationLogs(){
        $httpMethod = HttpMethod::GET;
        $this->prepareUserWithIdAndAuth(48,1);
        $uri = "/user/notification-log/list/?limit=10&list_type=".ListType::NOTIFICATION_LOG_USER;
        $content = $this->getJsonRequestContent($httpMethod,$uri);
        var_dump($content);
	    $this->printResponse($content);
	    $this->printSQLLog();
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }
}