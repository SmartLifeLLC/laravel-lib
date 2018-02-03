<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:14
 */

namespace Tests\Unit\MigrateTest\Models;


use App\Models\Old\NotificationLog;
use Tests\TestCase;

class NotificationLogTest extends TestCase
{
    function testNotificationLog(){
        $oldData = (new NotificationLog())->getData();
        var_dump($oldData);
    }
}