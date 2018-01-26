<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 7:02
 */

namespace Tests\Unit\Controllers;


use App\Constants\HttpMethod;
use App\Constants\ListType;
use App\Constants\StatusCode;
use App\Lib\Util;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContributionControllerTest extends TestCase
{

	public function setUp()
	{
		$this->userKey = 0;
		parent::setUp();
		parent::httpTestSetup();
		parent::prepareUser();
	}

	public function testCreate(){
		$httpMethod = HttpMethod::POST;
		Storage::fake('contribution');
		$uri = "/contribution/create";

		for($i = 0 ; $i < 100 ; $i++) {
			$this->prepareUser();
			$file = UploadedFile::fake()->image('test-image.png')->size(100);
			$data =
				[
					'product_item_id' => 100,
					'user_id' => $this->userId,
					'is_consent' => mt_rand(0,1),
					'text' => '商品評価 ' . Util::getRandomString(rand(10, 20)),
					'image1' => $file

				];
			$content = $this->getJsonRequestContent($httpMethod, $uri, $data);
			$this->printResponse($content);

		}
//		if(!isset($content['code']))  var_dump($content);
//		$this->printResponse($content);
//		//$this->printSQLLog();
//		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testEdit(){
		$httpMethod = HttpMethod::POST;
		$uri = "/contribution/edit";
		$data =
			[

				'user_id'=>$this->userId,
				'contribution_id'=>39,
				'text'=>'内容変更',
			];
		$content = $this->getJsonRequestContent($httpMethod,$uri,$data);

		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}


	public function testCheck(){
		$httpMethod = HttpMethod::GET;
		$uri = "/contribution/check/57";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testGetDetail(){
		$httpMethod = HttpMethod::GET;
		$feedId = 31;
		$uri = "/contribution/get/{$feedId}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testGetListForProduct(){
		$httpMethod = HttpMethod::GET;
		$productId = 101;
		$listType = ListType::CONTRIBUTION_LIST_FOR_PRODUCT;
		$uri = "/contribution/list/{$productId}?page=1&feeling=positive&listType={$listType}";

		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testGetListForInterest(){
		$httpMethod = HttpMethod::GET;
		$ownerId = 6577;
		$listType = ListType::CONTRIBUTION_LIST_FOR_USER_INTEREST;

		$uri = "/contribution/list/{$ownerId}?page=1&listType={$listType}";

		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testGetListForUser(){
		$httpMethod = HttpMethod::GET;
		$ownerId = 48;
		$listType = ListType::CONTRIBUTION_LIST_USER;
		$uri = "/contribution/list/{$ownerId}?page=1&listType={$listType}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		//var_dump($content);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}


	public function testGetListForFeed(){
		$httpMethod = HttpMethod::GET;
		$listType = ListType::CONTRIBUTION_LIST_FEED;
		$this->prepareUserWithIdAndAuth(48,1);
		$uri = "/contribution/list/62?listType={$listType}&feelingType=all";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testDelete(){
		$userId = 48;
		$auth = "5a4ca9c659465";
		$this->prepareUserWithIdAndAuth($userId,$auth);
		$httpMethod = HttpMethod::DELETE;
		$feedId = 33;
		$uri = "/contribution/delete/{$feedId}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

}