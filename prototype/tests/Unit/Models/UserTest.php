<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 12:58
 */

namespace Tests\Unit\Models;


use App\Lib\RandomFacebookUser;
use App\Lib\Util;
use App\Models\User;
use App\ValueObject\FacebookResponseVO;
use Tests\TestCase;

class UserTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	public function testCreateUser(){
		for($i = 0 ; $i < 10000 ; $i ++) {
			$facebookUser = (new RandomFacebookUser(Util::getUniqueNumber()))->getData();
			$facebookResponseVO = new FacebookResponseVO($facebookUser);
			$auth = uniqid("AUTH_");
			$userId = (new User())->createUserDataAndGetId($facebookResponseVO, $auth);
		}
		var_dump($userId);
	}

}