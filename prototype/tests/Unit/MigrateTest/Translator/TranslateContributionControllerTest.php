<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 2:12
 */

namespace Tests\Unit\MigrateTest\Translator;


use App\Http\Controllers\Translate\TranslateContributionController;
use Tests\TestCase;

class TranslateContributionControllerTest extends TestCase
{
    function testContribution(){
        $TranslateResult = (new TranslateContributionController())->translatePreviousData();
        var_dump($TranslateResult);
    }
}