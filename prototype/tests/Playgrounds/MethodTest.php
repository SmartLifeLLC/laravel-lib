<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/02/01
 * Time: 12:30
 */
namespace Tests\Unit\Playgrounds;
use App\Constants\DefaultValues;
use Tests\TestCase;

class MethodTest extends TestCase
{

	public function testArrayIntersect(){
		$orderString ="positive_count,positive_Count,Contribution_count,negative_count,display_name,a,bc,";

//		var_dump($orderList);
	}

	public function testSprintf(){
		$urlFormat = "https://graph.facebook.com/%s/friends?access_token=EAAEFt83nGV4BACn1kY66nefV22tYZByweasyt9grC50vUP9TSkmh2v0k2Qqzm22nBvz0mIdnIWUgx1QM3pzjLAZCT5mlRAOUFKSNAZCCtMUw3NU5pDRKLtOFuRb2HOvbU6ngFWmxsVpfVhecCnmaOtDvHPpnZBN9aVglHnm0ZBIBdFAtB82X6GJqkBaisKq0ZBlbNV2U15MJr3AKdj9RrCVL99u7GrQsEkm4OM4cWYKQZDZD&limit=25&%s";
		$endpoint = sprintf($urlFormat,"123123213","4444");
		var_dump($endpoint);
	}
}