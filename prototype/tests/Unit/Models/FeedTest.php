<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 5:00
 */

namespace Tests\Unit\Models;


use App\Models\Feed;
use Tests\TestCase;

class FeedTest extends TestCase
{
	public function testGetDetail(){
		$userId = 48;
		$feedId = 28;
		$result = (new Feed())->getDetail($userId,$feedId);
		var_dump($result);
	}
}