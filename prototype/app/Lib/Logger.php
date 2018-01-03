<?php
/**
 * class Logger
 * @package App\Lib
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */

namespace App\Lib;


use App\Constants\HeaderKeys;
use App\Constants\StatusMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Logger extends Log
{
    static function requestError(Request $request,$errorCode){
        $message = StatusMessage::get($errorCode);
        $userId = $request->header(HeaderKeys::REACT_USER_ID);
        $auth = $request->header(HeaderKeys::REACT_AUTH);
        Log::error("Error for request : " . $request->fullUrl() . " by user id : ". $userId. " with auth : " . $auth );
        Log::error($message);
    }

    static function serverError($exception){
        Log::error($exception->getTraceAsString());
    }

    /**
     * @param $parameters
     * @param $message
     */
    static function serviceError(Array $parameters, $message){
        Log::error("Service Error : " . $message);
        Log::error($parameters);
    }
}