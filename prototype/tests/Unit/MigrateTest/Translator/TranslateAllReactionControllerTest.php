<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/02/01
 * Time: 17:39
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateAllReactionController;
use App\Models\Old\User;
use Tests\TestCase;

class TranslateAllReactionControllerTest extends TestCase
{
	function testSomething(){
		echo "RUN TEST";
		$data1 = "a";
		$data2 = "a";
		$this->assertEquals($data1,$data2);
	}

	function testUser(){
		$TranslateResult = (new TranslateAllReactionController())->translatePreviousData();
		var_dump($TranslateResult);

	}
}