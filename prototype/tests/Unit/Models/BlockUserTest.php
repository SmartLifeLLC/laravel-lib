<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/26
 * Time: 11:47
 */

namespace Tests\Unit\Models;


use App\Models\BlockUser;
use Tests\TestCase;

class BlockUserTest extends TestCase
{
	public function testGetBlockAndBlockedUserList(){
		$blockModel = new BlockUser();
		$userId = 48;
		$ownerId = 49;
		$result = $blockModel->getBlockAndBlockedUserIds($userId,$ownerId);
		var_dump($result);
		$this->printSQLLog();
	}
}