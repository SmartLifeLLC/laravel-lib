<?php
/**
 *  Class FollowTask
 * @package App\Services\Tasks
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/18
 */

namespace App\Services\Tasks;
use App\Constants\URLs;
use App\Lib\JSYService\ServiceTask;


class CheckFacebookTokenTask implements ServiceTask
{

    /**
     * @var String
     */
    private $facebookId;

    /**
     * @var String
     */
    private $facebookToken;


    private $result;

    /**
     * CheckFacebookToken constructor.
     * @param String $facebookId
     * @param String $facebookToken
     */
    public function __construct(String $facebookId,String $facebookToken)
    {
        parent::__construct();
        $this->facebookId = $facebookId;
        $this->facebookToken = $facebookToken;
    }


    /**
     *
     */
    function run()
    {
        //Start Curl
        $url = URLs::API_FB_USER_INFO.$this->facebookToken;
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'GET');
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($curl);
        $this->result = json_decode($result,true);
        curl_close($curl);
//        //Check response
//        if(array_key_exists('error',$this->taskData)){
//            return TaskCondition::FACEBOOK_USER_API_FAILED;
//        }else{
//            if($this->taskData['id'] != $this->facebookId)
//                return TaskCondition::FACEBOOK_USER_API_ID_FAILED;
//            return TaskCondition::FACEBOOK_USER_API_SUCCEED;
//        }
    }

}