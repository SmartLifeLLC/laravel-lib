<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:00
 */

namespace App\Http\JsonView\Translate;

use App\Http\JsonView\JsonResponseView;

class PreviousContributionJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'contribution_id'=>$this->data,
            'message' => "アリナシ投稿の移行に成功しました。"
        ];
    }
}