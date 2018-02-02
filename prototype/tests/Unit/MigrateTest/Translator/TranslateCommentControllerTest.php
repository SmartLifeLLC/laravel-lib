<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 1:33
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateCommentController;
use Tests\TestCase;

class TranslateCommentControllerTest extends TestCase
{
    function testComment(){
        $TranslateResult = (new TranslateCommentController())->translatePreviousData();
        var_dump($TranslateResult);
    }
}