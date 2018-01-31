<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/31
 * Time: 21:58
 */

namespace App\Http\JsonView\Translate;


class PreviousDeletedContentJsonView
{

    function createBody()
    {
        $this->body = [
            'contribution_id'=>$this->data,
            'message' => "削除済み投稿の移行に成功しました。"
        ];
    }
}