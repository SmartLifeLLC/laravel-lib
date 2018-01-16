<?php

namespace Tests\Unit\Services;

use App\Constants\HttpMethod;
use App\Services\AuthService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthServiceTest extends TestCase
{

    //Get random user id  and auth data from server.
    protected $authUserId = 48;
    protected $auth = "5a4ca9c659465";


    public function testIsAuth(){
        $authService = new AuthService();
        $serviceResult = $authService->isValidUser($this->authUserId,$this->auth,HttpMethod::GET);
	    var_dump($serviceResult->getDebugMessage());
	    $this->assertTrue($serviceResult->getResult());
    }

}
