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
use App\ValueObject\UserVO;

class GetIdAndAuthJsonView extends JsonResponseView
{

    /**
     * @var UserVO
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

    /**
     * @param ServiceResult $serviceResult
     * @return JsonResponseView
     */
    public static function createJsonView(ServiceResult $serviceResult){
        if($serviceResult->getResult()==null){
            return JsonResponseView::withErrorServiceResult($serviceResult);
        }

        $userVO = $serviceResult->getResult();
        $jsonResponseView = GetIdAndAuthJsonView::withSuccessData($userVO);
        return $jsonResponseView;
    }
}