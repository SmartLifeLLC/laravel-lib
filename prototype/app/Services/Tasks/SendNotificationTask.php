<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:30
 */

namespace App\Services\Tasks;


use App\Lib\JSYService\ServiceTask;

class SendNotificationTask implements ServiceTask
{

	/**
	 * Reaction notification
	 */
	public function sendReactionNotification(){
		//todo implement send reaction notification.
	}

	/**
	 * @param $userId
	 * @param $targetUserId
	 * @param $commentId
	 * @param $content
	 */
	public function sendCommentNotification($userId,$targetUserId,$commentId,$content){
		//todo implement send comment notification.
	}

	function run()
	{
		// TODO: Implement run() method.
	}

	function getResult()
	{
		// TODO: Implement getResult() method.
	}

}