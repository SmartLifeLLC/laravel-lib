<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 5:00
 */

namespace Tests\Unit\Models;


use App\Models\Contribution;
use Tests\TestCase;

class ContributionTest extends TestCase
{
	public function testGetDetail(){
		$userId = 48;
		$feedId = 28;
		$result = (new Contribution())->getDetail($userId,$feedId);
		var_dump($result);
	}

	public function testGetCountForUser(){
		$userId = 48;
		$blockList = [49,50,902];
		$result = (new Contribution())->getCountForOwnerInterest($userId,$blockList);
		$this->printSQLLog();
		print_r($result);
	}

	public function testGetContributionListForOwnerInterest(){
		$userId = 48;
		$ownerId = 49;
		$blockList = [];
		$result = (new Contribution())->getListForOwnerInterest($userId,$ownerId,$blockList,1,10);
		print_r($result);
		$this->printSQLLog();
	}

}