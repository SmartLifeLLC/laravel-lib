<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/30
 * Time: 16:17
 */

namespace App\Http\JsonView\Translate;

use App\Http\JsonView\JsonResponseView;

class PreviousImageJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'image_id'=>$this->data,
            'message' => "画像の移行に成功しました。"
        ];
    }
}