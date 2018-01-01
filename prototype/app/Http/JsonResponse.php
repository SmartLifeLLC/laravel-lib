<?php
/**
 * class JsonResponse
 * @package App\Http
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */

namespace App\Http;


use App\Constants\StatusMessage;
use App\Constants\Versions;

class JsonResponse
{

    private $status;
    private $code;
    private $data;

    public function __construct() {
        // allocate your stuff
    }

    public static function withSuccessData( $data, $code ) {
        $instance = new self();
        $instance->data = $data;
        $instance->code = $code;
        $instance->status = "OK";
        return $instance;
    }

    public static function withErrorCode( $code ) {
        $instance = new self();
        $instance->status = "ERROR";
        $instance->code = $code;
        $instance->data = ["message"=>StatusMessage::get($code)];
        return $instance;
    }



    public function getResponseData(){
        $response =
            [
                'version'=>Versions::CURRENT,
                'status'=>$this->status,
                'code'=>$this->code,
                'body'=>base64_encode(json_encode($this->data))
            ];
        return json_encode($response);
    }
}