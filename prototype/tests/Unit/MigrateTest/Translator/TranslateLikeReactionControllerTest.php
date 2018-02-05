<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:32
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateLikeReactionController;
use Tests\TestCase;

class TranslateLikeReactionControllerTest extends TestCase
{
    function testLikeReaction(){
        $TranslateData = (new TranslateLikeReactionController())->translatePreviousData();
        var_dump($TranslateData);
    }
}