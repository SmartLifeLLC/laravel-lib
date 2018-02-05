<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/02
 * Time: 14:07
 */

namespace Tests\Unit\MigrateTest\Models;

use App\Models\Old\User;
use Tests\TestCase;
class UserTest extends TestCase
{

    function testUser(){
        $OldData = (new User())->getData();
        var_dump($OldData);
    }
}