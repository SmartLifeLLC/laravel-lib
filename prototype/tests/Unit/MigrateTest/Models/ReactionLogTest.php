<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:01
 */

namespace Tests\Unit\MigrateTest\Models;


use App\Models\Old\ReactionLog;
use Tests\TestCase;

class ReactionLogTest extends TestCase
{
    function testReactionLog(){
        $oldData = (new ReactionLog())->getData();
        var_dump($oldData);
    }
}