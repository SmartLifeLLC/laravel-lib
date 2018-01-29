<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:52
 */

namespace App\Http\JsonView\Translate;

use App\Http\JsonView\JsonResponseView;

class PreviousHaveReactionJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'contribution_have_reaction_id'=>$this->data,
            'message' => "「持ってる」の移行に成功しました。"
        ];
    }
}