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
    static function requestError(Request $request,$errorCode,$debugMessage){
        $message = StatusMessage::get($errorCode);
        $userId = $request->header(HeaderKeys::REACT_USER_ID);
        $auth = $request->header(HeaderKeys::REACT_AUTH);
        Log::error("ERROR CODE : {$errorCode} Message : {$message} ");
        Log::error("Error for request : " . $request->fullUrl() . " by user id : ". $userId. " with auth : " . $auth );
        Log::error($debugMessage);
    }

    /**
     * @param $debugMessage
     */
    static function parameterError($debugMessage){
        Log::error($debugMessage);
    }

    static function serverError(\Exception $exception){
        Log::error($exception->getMessage());
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


    /**
     * @param $message
     */
    static function warning($message){
        Log::warning($message);
    }

	/**
	 * @param String $message
	 * @param array|null $data
	 */
    static function info(String $message,array $data = []){
        Log::info($message,$data);
    }
}