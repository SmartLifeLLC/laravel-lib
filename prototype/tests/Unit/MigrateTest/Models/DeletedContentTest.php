<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:12
 */

namespace Tests\Unit\MigrateTest\Models;


use App\Models\Old\DeletedContent;
use Tests\TestCase;

class DeletedContentTest extends TestCase
{
    function testDeletedContent(){
        $oldData = (new DeletedContent())->getData();
        var_dump($oldData);
    }
}