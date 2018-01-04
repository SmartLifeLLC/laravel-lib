<?php

namespace Tests\Unit\Services;

use App\Services\AuthService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthServiceTest extends TestCase
{

    //Get random user id  and auth data from server.
    private $authUserId = 48;
    private $auth = "5a4ca9c659465";


    public function testIsAuth(){
        $authService = new AuthService();
        $serviceResult = $authService->isValidAuth($this->authUserId,$this->auth);
        $this->assertTrue($serviceResult->getResult());
    }

}
