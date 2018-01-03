<?php
namespace App\Lib\JSYService;
use App\Constants\StatusCode;
use App\ValueObject\UserVO;

/**
 * Class ServiceResult
 * @package App\Lib\JSYService
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/01
 */
class ServiceResult
{

    /**
     * @var
     */
    private $result;



    /**
     * @var StatusCode
     */
    private $statusCode;

    /**
     * @var String
     */
    private $debugMessage;


    /**
     * @return mixed
     */
    function getResult(){
        return $this->result;
    }


    /**
     * @return StatusCode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return String
     */
    public function getDebugMessage(): String
    {
        return $this->debugMessage;
    }



    /**
     * ServiceResult constructor.
     * @param $result
     * @param $statusCode
     * @param String|null $debugMessage
     */
    public function __construct($result,$statusCode,$resultClass = null, String $debugMessage = null)
    {
        var_dump($result instanceof $resultClass);
        $this->result = $result;
        $this->statusCode = $statusCode;
        $this->debugMessage = $debugMessage;
    }

}