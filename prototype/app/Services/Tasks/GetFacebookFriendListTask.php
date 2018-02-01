<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/02/01
 * Time: 15:30
 */

namespace App\Services\Tasks;


use App\Constants\URLs;
use App\Lib\JSYService\ServiceTask;

class GetFacebookFriendListTask implements ServiceTask
{
	private $result;
	private $facebookUserId;
	private $facebookUserToken;
	private $after = "";

	public function __construct($facebookUserId,$facebookUserToken,$after = null)
	{
		$this->facebookUserId = $facebookUserId ;
		$this->facebookUserToken = $facebookUserToken;
		$this->after = (!empty($after))?"after=".$after:"";
	}

	function run()
	{
		//Start Curl
		$url = sprintf(URLs::API_FB_USER_FRIEND_FORMAT,$this->facebookUserId,$this->facebookUserToken,$this->after);
		$curl = curl_init($url);
		curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'GET');
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		$result = curl_exec($curl);
		$result = json_decode($result,true);
		curl_close($curl);
		$this->result = $result;
	}

	function getResult()
	{
		return $this->result;
	}

}