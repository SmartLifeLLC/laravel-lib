<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:48
 */

namespace App\Http\JsonView\Translate;


use App\Http\JsonView\JsonResponseView;

class PreviousLikeReactionJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'contribution_like_reaction_id'=>$this->data,
            'message' => "「いいね」の移行に成功しました。"
        ];
    }
}