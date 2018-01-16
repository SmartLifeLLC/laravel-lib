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
use App\ValueObject\UserValidationVO;

class UserValidationJsonView extends JsonResponseView
{

    /**
     * @var UserValidationVO
     */
    protected $data;
    function createBody()
    {
        $this->body =
            [
                'user_id'=>$this->data->getUserId(),
                'auth' =>$this->data->getAuth(),
	            'limitation_level' => $this->data->getLimitationLevel()
            ];
    }
}