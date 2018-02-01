<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/29
 * Time: 16:14
 */


//https://firebase.google.com/docs/cloud-messaging/http-server-ref?hl=ja#notification-payload-support
//Payload category
//https://developer.apple.com/library/content/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/SupportingNotificationsinYourApp.html#//apple_ref/doc/uid/TP40008194-CH4-SW26
namespace App\Services\Tasks\NotificationTask;
use App\Constants\DateTimeFormat;
use App\Constants\SystemConstants;
use App\Constants\URLs;
use App\Lib\JSYService\ServiceTask;

class NotificationTask implements ServiceTask
{

	protected $message;
	protected $fromUserId;
	protected $targetUsers;
	protected $targetUserIds;
	protected $targetUserFBKeys;
	protected $contributionId;
	protected $contributionCommentId;
	protected $notificationLogTypeId;
	private $result;
	/**
	 * Notification constructor.
	 * @param $fromUserId
	 * @param $targetUsers
	 * @param $contributionId
	 * @param $contributionCommentId
	 * @param $notificationTypeId
	 * @param $message
	 */
	public function __construct($fromUserId, $targetUsers, $contributionId, $contributionCommentId, $notificationTypeId, $message)
	{
		$this->message = $message;
		$this->fromUserId = $fromUserId;
		$this->targetUsers = $targetUsers;
		$this->contributionCommentId = $contributionCommentId;
		$this->contributionId = $contributionId;
		$this->targetUserIds = [];
		$this->targetUserFBKeys = [];
		$this->notificationLogTypeId = $notificationTypeId;
		foreach($targetUsers as $userId => $fbKeys){
			$this->targetUserIds[] = $userId;
			foreach($fbKeys as $fbKey){
				$this->targetUserFBKeys[$fbKey] = $fbKey; //make unique
			}
		}
	}

	/**
	 * @return array
	 */
	function getSaveData():array{
		$data = [];
		foreach ($this->targetUserIds as $targetUserId){
		$data[] =
			[
				'target_user_id' => $targetUserId,
				'from_user_id'   => $this->fromUserId,
				'message'        => $this->message,
				'delivered_at'   => date(DateTimeFormat::General),
				'contribution_id' => $this->contributionId,
				'contribution_comment_id' => $this->contributionCommentId,
				'notification_log_type_id' => $this->notificationLogTypeId
			];
		}
		return $data;
	}


	/**
	 * @return mixed|void
	 */
	public function run(){


		if(empty($this->targetUserFBKeys)) {
			$this->result = "No notification tokens ";
			return;
		}

		//Send to Firebase
		$headers = [
			"Authorization: key=". SystemConstants::getFirebaseKey(),
			"Content-Type: application/json"
		];
		$fields = [
			"registration_ids" => array_values($this->targetUserFBKeys),
			"notification" => [
				"text" => $this->message
			]
		];
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, URLs::FIREBASE_FCM_ENDPOINT);
		curl_setopt($handle, CURLOPT_POST, true);
		curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($handle);
		curl_close($handle);
		$this->result = $result;


	}

	function getResult()
	{
		return $this->result;
	}
}