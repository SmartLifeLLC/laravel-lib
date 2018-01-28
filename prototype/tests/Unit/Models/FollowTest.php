<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/26
 * Time: 12:36
 */

namespace Tests\Unit\Models;


use App\Models\Follow;
use App\Models\Follower;
use Tests\TestCase;

class FollowTest extends TestCase
{
	public function testFollowCount()
	{
		$userId = 48;
		$count = (new Follow())->getCountForUser($userId,[49]);
		$this->printSQLLog();
		print_r($count);
	}
}