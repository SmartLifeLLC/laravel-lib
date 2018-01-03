<?php
/**
 * class JsonResponse
 * @package App\Http
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */

namespace App\Http\JsonView;


use App\Constants\StatusCode;
use App\Constants\StatusMessage;
use App\Constants\Versions;
use App\Lib\JSYService\ServiceResult;
use Psy\Util\Json;

/**
 * Class JsonResponseView
 * @package App\Http
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */
class JsonResponseView
{

    /**
     * @var
     */
    private $status;
    /**
     * @var
     */
    private $code;
    /**
     * @var
     */
    protected $data;
    /**
     * @var array
     */
    protected $body = [];
    /**
     * @var string
     */
    protected $errorMsg ="" ;

    /**
     * JsonResponseView constructor.
     */
    public function __construct() {
        // allocate your stuff
    }

    /**
     * @param $message
     */
    public function appendErrorMessage($message){
        $this->errorMsg .= $message;
    }

    /**
     * @param $data
     * @return JsonResponseView
     */
    public static function withSuccessData($data) {
        $instance = new self();
        $instance->data = $data;
        $instance->code = StatusCode::SUCCESS;
        $instance->status = "OK";
        return $instance;
    }

    /**
     * @param $code
     * @param string $errorMessage
     * @return JsonResponseView
     */
    public static function withErrorCode($code, $errorMessage = "") {
        $instance = new self();
        $instance->status = "ERROR";
        $instance->code = $code;
        $instance->body = ["message"=>StatusMessage::get($code),"detail"=>$errorMessage];
        return $instance;
    }


    /**
     * @param ServiceResult $serviceResult
     * @return JsonResponseView
     */
    public static function withErrorServiceResult(ServiceResult $serviceResult) {

        return JsonResponseView::withErrorCode($serviceResult->getStatusCode(),$serviceResult->getDebugMessage());
    }
    //Must override this method for making body

    /**
     *
     */
    protected function createBody(){

    }

    /**
     * @return string
     */
    public function createJsonString(){
        $this->createBody();
        $response =
            [
                'version'=>Versions::CURRENT,
                'status'=>$this->status,
                'code'=>$this->code,
                'body'=>base64_encode(json_encode($this->body))
            ];
        return json_encode($response);
    }
}