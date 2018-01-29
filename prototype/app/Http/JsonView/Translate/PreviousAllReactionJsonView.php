<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:52
 */

namespace App\Http\JsonView\Translate;

use App\Http\JsonView\JsonResponseView;

class PreviousAllReactionJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'contribution_all_reaction_id'=>$this->data,
            'message' => "リアクションの移行に成功しました。"
        ];
    }
}