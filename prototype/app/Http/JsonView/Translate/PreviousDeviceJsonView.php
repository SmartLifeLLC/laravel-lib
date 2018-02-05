<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/31
 * Time: 22:04
 */

namespace App\Http\JsonView\Translate;

use App\Http\JsonView\JsonResponseView;

class PreviousDeviceJsonView extends JsonResponseView
{

    function createBody()
    {
        $this->body = [
            'contribution_id'=>$this->data,
            'message' => "デバイス情報の移行に成功しました。"
        ];
    }
}