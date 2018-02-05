<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 20:09
 */

namespace Tests\Unit\MigrateTest\Models;


use App\Models\Old\ReviewPostComment;
use Tests\TestCase;

class ReviewPostCommentTest extends TestCase
{
    function testReviewPostComment(){
        $oldData = (new ReviewPostComment())->getData();
        var_dump($oldData);
    }
}