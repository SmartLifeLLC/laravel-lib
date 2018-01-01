<?php
namespace App\Lib\JSYService;

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
     * @var \Exception|null
     */
    private $exception;
    /**
     * @var bool
     */
    private $isFinishedTask;


    private $statusCode;


    /**
     * @return mixed
     */
    function getResult(){
        return $this->result;
    }

    /**
     * @return \Exception|null
     */
    function getException(){
        return $this->exception;
    }


    /**
     * ServiceResult constructor.
     * @param $result
     * @param $statusCode
     * @param \Exception|null $exception
     */

    public function __construct($result,$statusCode, ?\Exception $exception)
    {
        $this->result = $result;
        $this->statusCode = $statusCode;
        $this->isFinishedTask = ($exception == null)?true:false;
        $this->exception = $exception;
    }
}