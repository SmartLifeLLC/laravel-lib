<?php
namespace App\Http\Controllers\User;
use App\Constants\HeaderKeys;
use App\Constants\StatusCode;
use App\Http\Controllers\Controller;
use App\Http\JsonResponseView;
use App\Http\JsonView\User\Auth\GetIdAndAuthJsonView;
use App\Services\AuthService;
use Illuminate\Http\Request;

/**
 * class GetAuthController
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @param $facebookId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdAndAuth(Request $request, $facebookId){
        $facebookToken = $request->header(HeaderKeys::FB_TOKEN);
        if(empty($facebookId)||empty($facebookToken )){
            $jsonResponseView =
                JsonResponseView::withErrorCode(
                    StatusCode::REQUEST_PARAMETER_ERROR,
                    "Facebook ID : {$facebookId}, Facebook Token : {$facebookToken}"
                );
            return $this->responseJson($jsonResponseView);
        }

        $serviceResult = (new AuthService())->getIdAndAuth($facebookId,$facebookToken);
        $jsonResponseView = GetIdAndAuthJsonView::createJsonView($serviceResult);
        return $this->responseJson($jsonResponseView);
    }
}