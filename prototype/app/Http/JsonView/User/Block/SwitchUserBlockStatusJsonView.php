<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 17:04
 */

namespace App\Http\JsonView\User\Block;

use App\Http\JsonView\JsonResponseView;

class SwitchUserBlockStatusJsonView extends JsonResponseView
{

    function createBody()
    {
        if($this->data === true){
            $this->body = ["message"=>"Success"];
        }else{
            $this->body = ["message"=>"Failed to switch block status for unknown reason"];
        }
    }
}