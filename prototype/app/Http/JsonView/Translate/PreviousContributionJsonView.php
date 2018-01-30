<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:00
 */

namespace App\Http\JsonView\Translate;


class PreviousContributionJsonView
{

    function createBody()
    {
        $this->body = [
            'contribution_id'=>$this->data,
            'message' => "アリナシ投稿の移行に成功しました。"
        ];
    }
}