<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:57
 */

namespace App\Http\JsonView\Translate;


use App\Http\JsonView\JsonResponseView;

class PreviousReactionNotificationJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'image_id' => $this->data,
            'message' => "リアクションの通知の移行に成功しました。"
        ];
    }
}