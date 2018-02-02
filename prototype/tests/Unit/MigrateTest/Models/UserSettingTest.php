<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/02
 * Time: 22:26
 */

namespace Tests\Unit\MigrateTest\Models;


use App\Models\Old\UserSetting;
use Tests\TestCase;

class UserSettingTest extends TestCase
{
    function testUserSetting(){
        $OldData = (new UserSetting())->getData(1);
        var_dump($OldData);
    }
}