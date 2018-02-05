<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:15
 */

namespace Tests\Unit\MigrateTest\Models;


use App\Models\Old\NotificationToken;
use Tests\TestCase;

class NotificationTokenTest extends TestCase
{
    function testNotificationToken(){
        $oldData = (new NotificationToken())->getData();
        var_dump($oldData);
    }
}