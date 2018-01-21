<?php
/**
 * class JsonResponse
 * @package App\Http
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */

namespace App\Http\JsonView;


use App\Constants\FeedFeelingType;
use App\Constants\SystemConstants;
use App\Constants\Gender;
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


    //todo change createBody to getBody
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

	/**
	 * @param $birthday
	 * @param $isPermitted
	 * @return string
	 */

    public function getBirthdayString($birthday,$isPermitted){
	    if(!$isPermitted)
		    return "非公開";
	    else {
	    	if(strpos($birthday,' ')!==false)
		        return explode(' ', $birthday)[0];
	    	return $birthday;
	    }
	}


	/**
	 * @param $genderType
	 * @param $isPermitted
	 * @return string
	 */
    public function getGenderString($genderType,$isPermitted){
    	if(!$isPermitted)
    		return "非公開";
    	else

    		return Gender::getString($genderType);
    }

	/**
	 * @param $s3key
	 * @return string
	 */
    public function getImageURLForS3Key($s3key){
    	return  (empty($s3key)) ? "" : SystemConstants::getCdnHost().$s3key;
    }

	/**
	 * @param $value
	 * @return int
	 */
    public function getBinaryValue($value){
    	if(empty($value))
    		return 0;
    	else
    		return 1;
    }

	/**
	 * @param $stringValue
	 * @return int
	 */
    public function getFeelingBinaryValue($stringValue){
    	return FeedFeelingType::getBinaryValue($stringValue);
    }


	/**
	 * @param array ...$s3Keys
	 * @return array
	 */
    public function getImageURLs(...$s3Keys){
    	$imageURLs = [];
    	foreach ($s3Keys as $s3Key){
    		if(!empty($s3Key)){
    			$imageURLs[] = $this->getImageURLForS3Key($s3Key);
		    }
	    }
	    return $imageURLs;

    }
}