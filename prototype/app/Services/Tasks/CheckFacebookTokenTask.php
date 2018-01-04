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
use App\ValueObject\FacebookResponseVO;


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

    /**
     * @var FacebookResponseVO
     */
    private $result;

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * CheckFacebookToken constructor.
     * @param String $facebookId
     * @param String $facebookToken
     */
    public function __construct(String $facebookId,String $facebookToken)
    {
        $this->facebookId = $facebookId;
        $this->facebookToken = $facebookToken;
    }



    /**
     *
     */
    function run():FacebookResponseVO
    {
        //Start Curl
        $url = URLs::API_FB_USER_INFO.$this->facebookToken;
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'GET');
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($curl);
        $result = json_decode($result,true);
        curl_close($curl);
        $this->result = $result;
        return new FacebookResponseVO($result);

    }

}