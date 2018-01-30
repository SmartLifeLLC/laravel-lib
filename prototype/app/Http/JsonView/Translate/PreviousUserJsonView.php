<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:56
 */

namespace App\Http\JsonView\Translate;


use App\Http\JsonView\JsonResponseView;
class PreviousUserJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'user_id'=>$this->data,
            'message' => "ユーザーの移行に成功しました。"
        ];
    }
}