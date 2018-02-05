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
	function testAllReaction(){
		$TranslateResult = (new TranslateAllReactionController())->translatePreviousData();
		var_dump($TranslateResult);
	}
}