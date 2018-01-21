<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:59
 */

namespace Tests\Unit\Controllers;


use App\Constants\FeedReactionType;
use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use Tests\TestCase;

class ReactionControllerTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
		parent::httpTestSetup();
		parent::prepareUser();
	}

	public function testCreateRandomReaction(){
		$httpMethod = HttpMethod::POST;

		for($i=0 ; $i < 100 ; $i++){
			parent::prepareUser();
			$reactionType = mt_rand(1,2);
			$feedId = mt_rand(25,33);
			$data =
				[
					'review_post_id'=>$feedId,
					'review_post_reaction_type'=>$reactionType,
				];
			$uri = "/feed/reaction/do";

			$content = $this->getJsonRequestContent($httpMethod,$uri,$data);
			$this->printResponse($content);
			if(!isset($content['code']))  var_dump($content);
		}
	}


	public function testLeaveReaction(){
		//$userId = 48;
		//$userAuth = "5a4ca9c659465";
		//parent::prepareUserWithIdAndAuth($userId,$userAuth);
		$feedId = 39;


		$httpMethod = HttpMethod::POST;
		$uri = "/feed/reaction/do";
		$data =
			[
				'review_post_id'=>$feedId,
				'review_post_reaction_type'=>FeedReactionType::LIKE,
			];
		$content = $this->getJsonRequestContent($httpMethod,$uri,$data);

		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

	public function testCancelReaction(){

		$this->prepareUserWithIdAndAuth(3436,"AUTH_5a61c3bbc2a3d");
		$httpMethod = HttpMethod::POST;
		$uri = "/feed/reaction/cancel";
		$data =
			[
				'review_post_id'=>31,
				'review_post_reaction_type'=>FeedReactionType::HAVE,
			];
		$content = $this->getJsonRequestContent($httpMethod,$uri,$data);

		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}


	public function testGetList(){
		$httpMethod = HttpMethod::GET;
		$feedId = 25;
		$type = FeedReactionType::ALL;
		$page = 1;
		$uri = "/feed/reaction/list/".$feedId."?type={$type}&page={$page}";
		$content = $this->getJsonRequestContent($httpMethod,$uri);
		if(!isset($content['code']))  var_dump($content);
		$this->printResponse($content);
		//$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

}