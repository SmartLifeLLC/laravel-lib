<?php
namespace App\Lib\JSYService;
use App\Constants\StatusCode;
use App\Constants\StatusMessage;
use App\ValueObject\UserValidationVO;
use PhpParser\Node\Scalar\String_;

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
     * @var ?String
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
	 * @return null|String
	 */
    public function getDebugMessage():?String
    {
        return $this->debugMessage;
    }

    /**
     * @return String
     */
    public function getStatusMessage(): String
    {
        return StatusMessage::get($this->statusCode);
    }

    /**
     * ServiceResult constructor.
     * @param $result
     * @param $statusCode
     * @param String|null $debugMessage
     */
    private function __construct($result,$statusCode, String $debugMessage = null)
    {
        $this->result = $result;
        $this->statusCode = $statusCode;
        $this->debugMessage = $debugMessage;
    }

    /**
     * @param $statusCode
     * @param String|null $debugMessage
     * @return ServiceResult
     */
    public static function withError($statusCode,String $debugMessage = ""):ServiceResult
    {
        $instance = new self(null,$statusCode,$debugMessage);
        return $instance;
    }

	/**
	 * @param $userId
	 * @param $targetUserId
	 * @return ServiceResult
	 */
    public static function withBlockStatusError($userId, $targetUserId):ServiceResult
    {
	    $instance = new self(null,StatusCode::BLOCK_STATUS_WITH_TARGET_USER,"User {$userId} and {$targetUserId} are in block status");
	    return $instance;
    }

    /**
     * @param $result
     * @param null $resultClassForCheckingType
     * @return ServiceResult
     * @throws \TypeError
     */
    public static function withResult($result, $resultClassForCheckingType = null){
        $instance = new self($result,StatusCode::SUCCESS);
        if($resultClassForCheckingType != null){
            if(!$result instanceof $resultClassForCheckingType){
                throw new \TypeError("Current result does instance of ".$resultClassForCheckingType);
            }
        }
        return $instance;
    }

}