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
abstract class JsonResponseView
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
    private $debugMessage ="" ;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getDebugMessage(): string
    {
        return $this->debugMessage;
    }



    /**
     * JsonResponseView constructor.
     * @param ServiceResult $serviceResult
     */
    public function __construct(ServiceResult $serviceResult) {
        $this->code = $serviceResult->getStatusCode();
        if($serviceResult->getResult() === null){
            $this->status = "ERROR";
            $this->debugMessage = $serviceResult->getDebugMessage();
        }else{
            $this->data = $serviceResult->getResult();
            $this->status = "OK";

        }
    }



    //Must override this method for making body

    /**
     *
     */
    abstract function createBody();


    private function createErrorBody(){
        $this->body = [
            "message"=>StatusMessage::get( $this->getCode() ),
            "debug"=>$this->getDebugMessage()
        ];
    }

    /**
     * @return array
     */
    public function getResponse(){
        if($this->data !== null)  $this->createBody();
        else $this->createErrorBody();

        $response =
            [
                'version'=>Versions::CURRENT,
                'status'=>$this->status,
                'code'=>$this->code,
                'body'=>base64_encode(json_encode($this->body))
            ];
        return $response;
    }
}