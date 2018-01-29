<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/26
 * Time: 15:01
 */

namespace Tests\Unit\Models;


use App\Models\ContributionInterestReaction;
use Tests\TestCase;

class ContributionInterestReactionTest extends TestCase
{
	public function testGetCount(){
		$model = new ContributionInterestReaction();
		$userId = 62;
		$blockUsers = [48];
//		$result = $model->getCountForUser($userId,$blockUsers);
		$result = $model->getList($userId,26,$blockUsers,1,10);
		$this->printSQLLog();
	}
}