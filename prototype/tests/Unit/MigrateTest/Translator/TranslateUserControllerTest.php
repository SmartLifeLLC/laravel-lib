<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/02
 * Time: 14:14
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateUserController;
use Tests\TestCase;

class TranslateUserControllerTest extends TestCase
{
    function testUser(){
        $TranslateResult = (new TranslateUserController())->translatePreviousData();
        var_dump($TranslateResult);
    }
}