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
interface Notification
{
	public function getSaveData():array;

	public function send();

	public function setFromUserId(int $fromUserId);

	public function setTargetUserIds(array $targetUserIds);

}