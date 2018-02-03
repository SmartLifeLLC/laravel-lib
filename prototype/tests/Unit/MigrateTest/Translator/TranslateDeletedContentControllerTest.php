<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:24
 */

namespace Tests\Unit\MigrateTest\Translator;

use App\Http\Controllers\Translate\TranslateDeletedContentController;
use Tests\TestCase;

class TranslateDeletedContentControllerTest extends TestCase
{
    function testDeletedContent(){
        $TranslateResult = (new TranslateDeletedContentController())->translatePreviousData();
        var_dump($TranslateResult);
    }
}