<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/02
 * Time: 0:16
 */

namespace Tests\Unit\MigrateTest\Models;
use App\Models\Old\ReactionLog;
use Tests\TestCase;

class ReactionLogTest extends TestCase
{

    function testAllReaction(){
        $OldData = (new ReactionLog())->getData();
        echo($OldData);
    }
}