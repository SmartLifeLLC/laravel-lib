<?php
/**
 * class JsonResponse
 * @package App\Http
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */

namespace App\Http\JsonView;


use App\Constants\ContributionFeelingType;
use App\Constants\DefaultValues;
use App\Constants\HeaderKeys;
use App\Constants\SystemConstants;
use App\Constants\Gender;
use App\Constants\StatusCode;
use App\Constants\StatusMessage;
use App\Constants\Versions;
use App\Lib\JSYService\ServiceResult;
use App\Models\CurrentHeaders;
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
	    $needEncode  = (CurrentHeaders::shared()->get(HeaderKeys::REACT_SWAGGER_KEY) == DefaultValues::SWAGGER_TOKEN)?false:true;


	    if($this->data !== null)  $this->createBody();
        else $this->createErrorBody();

        if($needEncode){
        	$body = base64_encode(json_encode($this->body));
        }else {
        	$body = $this->body;
        }
        $response =
            [
                'version'=>Versions::CURRENT,
                'status'=>$this->status,
                'code'=>$this->code,
                'body'=>$body
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
    	return ContributionFeelingType::getBinaryValue($stringValue);
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

	/**
	 * @param $text
	 * @return string
	 */
    public function getNotNullString($text){
    	return ($text === null)?"":$text;
    }

    protected function getUserHashArray($userId,$userName,$profileImageS3Key,$followId,$description){
    	return [
			        'id' => $userId, //id
			        'un' => $userName, //user_name
			        'pu' => $this->getImageURLForS3Key($profileImageS3Key), //profile_image_url
			        "if" => $this->getBinaryValue($followId),//is_following
			        'ds' => $this->getNotNullString($description)
	            ]
		    ;
    }

	protected function getProductHashArray($productId,$name,$price,$contributionCount,$positiveCount,$negativeCount,$categories,$imageS3Key,$shops){
    	return
	        [
				"id" => $productId, //id
				"na" => $name, //name
				"pr" => (int) $price, //price
				"cc" => (int) $contributionCount, //contribution_count
				"pc" => (int) $positiveCount, //positive_count
				"nc" => (int) $negativeCount, //negative_count
				"ca" => array_values($categories), //array_values($productsCategories[$contribution['product_id']]),
				"iu" => $this->getImageURLForS3Key($imageS3Key), //image_url
				"sh" => $shops //shops
			];
	}

	/**
	 * @param $categoryId
	 * @param $categoryName
	 * @param $breadcrumb
	 * @param $productCount
	 * @return array
	 */
	protected function getWelFormedCategory($categoryId, $categoryName, $breadcrumb, $productCount){
		return
		[
			'id' =>$categoryId,
			'nm' =>$categoryName,
			'bc' =>$breadcrumb,
			'pn' =>$productCount,
		];
	}


    protected function getWellFormedContribution($contribution,$categories,$shops = []){
        $imageUrls = $this->getImageURLs($contribution['image_id_0'],$contribution['image_id_1'],$contribution['image_id_2'],$contribution['image_id_3']);
        $displayData =
            ["cb"=> //contribution
                [
                    'id' => $contribution['id'], //id
                    'tx' => $contribution['content'], //text
                    'fe' => $this->getFeelingBinaryValue($contribution['feeling']), //is_consent
                    'ca' => $contribution['contribution_created_at'], // created_at
                    'tc'=> (int) $contribution['total_reaction_count'], //total_reaction_count
                    'lc'=> (int) $contribution['like_reaction_count'], //like_reaction_count
                    'ic'=> (int) $contribution['interest_reaction_count'], //interest_reaction_count
                    'cc' => (int) $contribution['comment_count'], //comment_num
                    'iu' => $imageUrls, //image_urls
                    'il' => $this->getBinaryValue($contribution['contribution_like_reaction_id']), //is_like
                    'ii' => $this->getBinaryValue($contribution['contribution_interest_reaction_id']), //is_interest
                    "mc" => $this->getBinaryValue($contribution['my_contribution_id']), //is_contributed
                    'ur' => //user
                        $this->getUserHashArray(
                            $contribution['contribution_user_id'], //id
                            $contribution['user_name'], //user_name
                            $contribution['profile_image_s3_key'], //profile_image_url
                            $contribution['follow_id'],//is_following
	                        $contribution['description']
                        )
                ],
                "pi" => //product_item
                    $this->getProductHashArray(
	                    $contribution['product_id'],
	                    $contribution['display_name'],
	                    $contribution['price'],
	                    $contribution['contribution_count'],
	                    $contribution['positive_count'],
	                    $contribution['negative_count'],
	                    $categories,
	                    $contribution['product_image_s3_key'],
	                    $shops
                    )

            ];
        return $displayData;
    }
}