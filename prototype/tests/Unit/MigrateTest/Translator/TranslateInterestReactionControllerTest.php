<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:33
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateInterestReactionController;
use Tests\TestCase;

class TranslateInterestReactionControllerTest extends TestCase
{
    function testInterestReaction(){
        $TranslateData = (new TranslateInterestReactionController())->translatePreviousData();
        var_dump($TranslateData);
    }
}