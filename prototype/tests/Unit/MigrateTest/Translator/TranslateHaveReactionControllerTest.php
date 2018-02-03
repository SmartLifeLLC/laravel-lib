<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 21:05
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateHaveReactionController;
use Tests\TestCase;

class TranslateHaveReactionControllerTest extends TestCase
{
    function testHaveReaction(){
        $TranslateData = (new TranslateHaveReactionController())->translatePreviousData();
        var_dump($TranslateData);
    }
}