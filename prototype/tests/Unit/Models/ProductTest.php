<?php
/**
 * class ProductTest
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/14
 */
namespace Tests\Unit\Models;

use App\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function testKeywordSearch(){
        $product = new Product();
        $result = $product->searchKeyword("チキン");
        var_dump($result);
        $this->printSQLLog();
    }
}