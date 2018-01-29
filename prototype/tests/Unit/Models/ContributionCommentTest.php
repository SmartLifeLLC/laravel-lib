<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/28
 * Time: 23:49
 */

namespace Tests\Unit\Models;

use App\Models\ContributionComment;
use Tests\TestCase;

class ContributionCommentTest extends TestCase
{
	public function testGetBlockAndBlockedUserList(){
		$blockModel = new ContributionComment();
		$userId = 48;
		$ownerId = 49;
		$result = $blockModel->getBlockAndBlockedUserIds($userId,$ownerId);
		var_dump($result);
		$this->printSQLLog();
	}
}