<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/29
 * Time: 16:26
 */

namespace App\Services\Tasks\NotificationTask;

/**
 *
 * 他のユーザーからリアクションされたとき
リアクション内容を表示
あなたの投稿に○◯さんが○◯しました
　-{いいね|気になる}
　-ユーザー名　-最大文字数16文字

 * Class ReactionNotification
 * @package App\Services\Tasks\NotificationTask
 */
class ReactionNotification implements Notification
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