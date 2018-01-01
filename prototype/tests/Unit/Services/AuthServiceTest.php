<?php

namespace Tests\Unit\Services;

use App\Services\AuthService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testIsAuth(){
        $authService = new AuthService();
        $serviceResult = $authService->isValidAuth(12345,"12345");
    }
}
