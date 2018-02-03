<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 2:48
 */

namespace Tests\Unit\MigrateTest;


use App\Services\Translate\ChangeProductIdService;
use Tests\TestCase;

class ChangeProductIdServiceTest extends TestCase
{
    function testChangeProductId(){
        $id = 3;
        $newId = (new ChangeProductIdService())->getNewProductId($id);
        var_dump($newId);
    }
}