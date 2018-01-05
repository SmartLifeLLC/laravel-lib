<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 13:50
 */

namespace Tests\Unit\Controllers;
use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use Tests\TestCase;
class DeviceControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        parent::prepareAuth();
    }


    public function testRegisterDevice(){
        $httpMethod = HttpMethod::POST;
        $uri = "/user/device/register";
        $data = ['device_uuid'=>"TESTS_DEVICE_UUID",'notification_token'=>uniqid(),'device_type'=>'iPhone'];
        $content = $this->getJsonRequestContent($httpMethod,$uri,$data);
        $this->printResponse($content);
        $this->assertEquals(StatusCode::SUCCESS,$content["code"]);
    }

}