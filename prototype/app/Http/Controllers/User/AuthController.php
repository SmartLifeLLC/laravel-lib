<?php
namespace App\Http\Controllers\User;
use App\Constants\StatusCode;
use App\Http\Controllers\Controller;
use App\Http\JsonResponse;

/**
 * class GetAuthController
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */

class AuthController extends Controller
{
    public function __construct()
    {
    }

    public function get($facebookId){
        $jsonResponse = JsonResponse::withErrorCode(StatusCode::USER_UPDATE_ERROR);
        return response()->json($jsonResponse->getResponseData());
    }
}