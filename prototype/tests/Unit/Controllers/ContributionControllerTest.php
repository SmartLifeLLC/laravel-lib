<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 7:02
 */

namespace Tests\Unit\Controllers;


use App\Constants\HttpMethod;
use App\Constants\StatusCode;
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
		$uri = "/feed/contribution/create";
		$data =
			[
				'product_item_id'=>58,
				'user_id'=>$this->userId,
				'is_consent'=>1,
				'text'=>'商品評価',
				'to_having_reaction_review_post_id'=>null,
				'image1'=>UploadedFile::fake()->image('test-image.jpg')->size(100)

			];
		$content = $this->getJsonRequestContent($httpMethod,$uri,$data);

		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testEdit(){
		$httpMethod = HttpMethod::POST;
		$uri = "/feed/contribution/edit";
		$data =
			[

				'user_id'=>$this->userId,
				'review_post_id'=>39,
				'text'=>'内容変更',
			];
		$content = $this->getJsonRequestContent($httpMethod,$uri,$data);

		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}


	public function testFind(){
		$httpMethod = HttpMethod::POST;
		$uri = "/feed/contribution/find";
		$data =  ['product_item_id'=>57];
		$content = $this->getJsonRequestContent($httpMethod,$uri,$data);

		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testGetDetail(){
		$httpMethod = HttpMethod::GET;
		$feedId = 31;
		$uri = "/feed/contribution/detail/{$feedId}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testGetList(){
		$httpMethod = HttpMethod::GET;
		$productId = 101;
		$uri = "/feed/contribution/list/product/{$productId}?page=1&feeling=positive";

		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testGetInterestList(){
		$httpMethod = HttpMethod::GET;
		$ownerId = 6577;
		$uri = "/feed/contribution/list/interest/{$ownerId}?page=1";

		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testGetListForOwner(){
		$httpMethod = HttpMethod::GET;
		$ownerId = 48;
		$uri = "/feed/contribution/list/owner/{$ownerId}?page=1";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		//var_dump($content);
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
		$uri = "/feed/contribution/delete/{$feedId}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

}