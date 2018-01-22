<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:59
 */

namespace Tests\Unit\Controllers;


use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use Tests\TestCase;

class CommentControllerTest extends TestCase
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
		$uri = "/comment/create";
		$data =
			[
				'product_item_id'=>51,
				'review_post_id'=>31,
				'user_id'=>$this->userId,
				'is_consent'=>1,
				'text'=>'商品評価2',

			];
		$content = $this->getJsonRequestContent($httpMethod,$uri,$data);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testDelete(){
		$httpMethod = HttpMethod::DELETE;
		$commentId = 2;
		$uri = "/comment/delete/".$commentId;
		$content = $this->getJsonRequestContent($httpMethod,$uri);

		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testCommentList(){
		$httpMethod = HttpMethod::GET;
		$feedId = 39;
		$boundaryId = 0;
		$uri = "/comment/list/{$feedId}/{$boundaryId}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);

		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}
}