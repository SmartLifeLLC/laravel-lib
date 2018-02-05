<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 21:08
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateReactionNotificationController;
use Tests\TestCase;

class TranslateReactionNotificationControllerTest extends TestCase
{
    function testReactionNotification(){
        $TranslateData = (new TranslateReactionNotificationController())->translatePreviousData();
        var_dump($TranslateData);
    }
}