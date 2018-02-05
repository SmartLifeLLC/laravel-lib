<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 2:01
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateImageController;
use Tests\TestCase;

class TranslateImageControllerTest extends TestCase
{
    function testImage(){
        $TranslateResult = (new TranslateImageController())->translatePreviousData();
        var_dump($TranslateResult);
    }
}