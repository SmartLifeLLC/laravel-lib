<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/21
 * Time: 23:38
 */

namespace App\Http\JsonView\Translate;

use App\Http\JsonView\JsonResponseView;

class PreviousCommentJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'review_post_comment_id'=>$this->data,
            'message' => "コメントの移行に成功しました。"
        ];
    }
}