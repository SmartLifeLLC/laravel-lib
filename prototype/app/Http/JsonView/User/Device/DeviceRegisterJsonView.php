<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 13:48
 */

namespace App\Http\JsonView\Device;

use App\Http\JsonView\JsonResponseView;
class DeviceRegisterJsonView extends JsonResponseView
{
    protected $data;
    function createBody()
    {
        $message = "登録完了しました。";
        $this->body = ['message'=>$message];
    }
}