<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:57
 */

namespace App\Http\JsonView\Translate;

use App\Http\JsonView\JsonResponseView;

class PreviousNotificationLogJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'image_id' => $this->data,
            'message' => "通知の移行に成功しました。"
        ];
    }
}