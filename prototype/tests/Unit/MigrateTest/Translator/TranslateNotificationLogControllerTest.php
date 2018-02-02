<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 1:49
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateNotificationLogController;
use Tests\TestCase;

class TranslateNotificationLogControllerTest extends TestCase
{
    function testNotificationLog(){
        $TranslateResult = (new TranslateNotificationLogController())->translatePreviousData();
        var_dump($TranslateResult);
    }
}