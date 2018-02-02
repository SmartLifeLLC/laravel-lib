<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 0:36
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateDeviceController;
use Tests\TestCase;

class TranslateDeviceControllerTest extends TestCase
{
    function testDevice(){
        $TranslateResult = (new TranslateDeviceController())->translatePreviousData();
        var_dump($TranslateResult);
    }
}