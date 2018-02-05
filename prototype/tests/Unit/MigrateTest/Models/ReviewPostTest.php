<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:06
 */

namespace Tests\Unit\MigrateTest\Models;


use App\Models\Old\ReviewPost;
use Tests\TestCase;

class ReviewPostTest extends TestCase
{
    function testReviewPost(){
        $oldData = (new ReviewPost())->getData();
        var_dump($oldData);
    }
}