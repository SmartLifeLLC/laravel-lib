<?php
/**
 * class GetIdAndAuthJsonView
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */
namespace App\Http\JsonView\User\Auth;

use App\Http\JsonView\JsonResponseView;
use App\Lib\JSYService\ServiceResult;
use App\ValueObject\UserAuthVO;

class GetIdAndAuthJsonView extends JsonResponseView
{

    /**
     * @var UserAuthVO
     */
    protected $data;
    function createBody()
    {
        $this->body =
            [
                'user_id'=>$this->data->getUserId(),
                'auth' =>$this->data->getAuth()
            ];
    }
}