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
		$blockUsers = [];
		$result = $model->getCountForUser($userId,$blockUsers);
		print_r($result);
		$this->printSQLLog();
	}
}