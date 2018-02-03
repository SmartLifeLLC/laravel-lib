<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:15
 */

namespace Tests\Unit\MigrateTest\Models;


use App\Models\Old\Content;
use Tests\TestCase;

class ContentTest extends TestCase
{
    function testContent(){
        $oldData = (new Content())->getData();
        var_dump($oldData);
    }
}