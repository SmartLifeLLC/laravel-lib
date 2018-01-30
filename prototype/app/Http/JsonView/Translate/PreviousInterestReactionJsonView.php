<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:51
 */

namespace App\Http\JsonView\Translate;

use App\Http\JsonView\JsonResponseView;

class PreviousInterestReactionJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'contribution_interest_reaction_id'=>$this->data,
            'message' => "「気になる」の移行に成功しました。"
        ];
    }
}