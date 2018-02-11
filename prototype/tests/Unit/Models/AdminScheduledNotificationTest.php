<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/02/07
 * Time: 13:33
 */

namespace Tests\Unit\Models;


use App\Models\AdminScheduledNotification;
use Tests\TestCase;

class AdminScheduledNotificationTest extends TestCase
{
	public function testGetNextNotificationData(){
		$data = (new AdminScheduledNotification())->getNextGlobalNotificationData();
		var_dump($data);
	}
}