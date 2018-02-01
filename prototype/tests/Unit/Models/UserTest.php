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


	public function testGetFacebookFeaturedUser(){
		$this->prepareUserWithIdAndAuth(48,1);
		$userModel = new User();
		$userInfo = $userModel->getUserInfo($this->userId);
		$facebookIds = [
			51=>'1516356446126484',
			52=>'1516356537852215',
			53=>'1516356537858727',
			54=>'1516356537859385',
			55=>'1516356537859995',
			56=>'1516356537860575',
			57=>'1516356537861156',
			58=>'1516356537861737',
			59=>'1516356537862289',
			60=>'1516356537862836',
			61=>'1516356537863432',
			62=>'1516356537863954',
			63=>'1516356537864549',
			64=>'1516356537865219',
			65=>'1516356537865776',
			66=>'1516356537866425',
			67=>'1516356537867021',
			68=>'1516356537867563',
			69=>'1516356537868153',
			70=>'1516356537868731',
			71=>'1516356537869329',
			72=>'1516356537869943',
			73=>'1516356537870570',
			74=>'1516356537871195',
			75=>'1516356537871788',
			76=>'1516356537872320',
			77=>'1516356537872949'
		];


		$users = $userModel->getFeaturedUserListFromFacebookIdsWithCount($this->userId,$userInfo['gender'],$userInfo['birthday'],array_values($facebookIds),25,1);
		print_r($users->isHasNext());
		print_r("\r\n");
		print_r($users->getCount());
		$this->printSQLLog();
	}
}