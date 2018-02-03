<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:21
 */

namespace Tests\Unit\MigrateTest\Models;


use App\Models\Old\ProductItemCategory;
use Tests\TestCase;

class ProductItemCategoryTest extends TestCase
{
    function testProductItemCategory(){
        $oldData = (new ProductItemCategory())->getJanCode(1);
        var_dump($oldData->jan_code);
    }
}