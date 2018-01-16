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
		parent::setUp();
		parent::prepareAuth();
	}

	public function testCreate(){
		$httpMethod = HttpMethod::POST;
		Storage::fake('contribution');
		$uri = "/feed/contribution/create";
		$data =
			[
				'product_item_id'=>51,
				'user_id'=>$this->userId,
				'is_consent'=>1,
				'text'=>'商品評価',
				'image1'=>UploadedFile::fake()->image('test-image.jpg')->size(100)

			];
		$content = $this->getJsonRequestContent($httpMethod,$uri,$data);

		if(!isset($content['code']))  var_dump($content);

		$this->printResponse($content);
		$this->printSQLLog();
		$this->assertEquals(StatusCode::SUCCESS,$content["code"]);
	}

}