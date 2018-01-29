<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/29
 * Time: 16:25
 */

namespace App\Services\Tasks\NotificationTask;


/**
 * フォロー通知
   ○○さんがあなたをフォローしました
   ユーザー名最大16文字

 * Class FollowNotification
 * @package App\Services\Tasks\NotificationTask
 */
class FollowNotification implements Notification
{
	public function getSaveData(): array
	{
		// TODO: Implement getSaveData() method.
	}

	public function send()
	{
		// TODO: Implement send() method.
	}

	public function setFromUserId(int $fromUserId)
	{
		// TODO: Implement setFromUserId() method.
	}

	public function setTargetUserIds(array $targetUserIds)
	{
		// TODO: Implement setTargetUserIds() method.
	}

}