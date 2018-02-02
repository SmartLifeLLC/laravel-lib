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
	private $facebookIds = [];

	public function __construct($facebookUserId,$facebookUserToken,$after = null)
	{
		$this->facebookUserId = $facebookUserId ;
		$this->facebookUserToken = $facebookUserToken;
		$this->after = (!empty($after))?"after=".$after:"";
	}

	function run()
	{
		$fbResult = $this->getDataFromFacebook();
		if(isset($fbResult['error'])){
			$this->result = $fbResult;
			return;
		}else{
			foreach($fbResult['data'] as $friend){
				$this->facebookIds[] = $friend['id'];
			}

			if(isset($fbResult['paging'])&&isset($fbResult['paging']['cursors'])&&isset($fbResult['paging']['cursors']['after'])){
				$this->after = $fbResult['paging']['cursors']['after'];
			}else{
				$this->after = null;
			}
		}

		while (!empty($this->after)) {
			$fbResult = $this->getDataFromFacebook();
			if (isset($fbResult['error'])) {
				$this->result = $fbResult;
				return;
			}

			foreach ($fbResult['data'] as $friend) {
				$this->facebookIds[] = $friend['id'];
			}
			if (isset($fbResult['paging']) && isset($fbResult['paging']['cursors']) && isset($fbResult['paging']['cursors']['after'])) {
				$this->after = $fbResult['paging']['cursors']['after'];
			} else {
				$this->after = null;
			}
		}
		return ;
	}

	private function getDataFromFacebook(){
		//Start Curl
		$url = sprintf(URLs::API_FB_USER_FRIEND_FORMAT,$this->facebookUserId,$this->facebookUserToken,"after=".$this->after);
		$curl = curl_init($url);
		curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'GET');
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		$result = curl_exec($curl);
		$result = json_decode($result,true);
		curl_close($curl);
		return $result;
	}


	/**
	 * @return array
	 */
	function getResult()
	{

		if(isset($this->result['error']))
			return $this->result;
		else
			return $this->facebookIds;
	}

}