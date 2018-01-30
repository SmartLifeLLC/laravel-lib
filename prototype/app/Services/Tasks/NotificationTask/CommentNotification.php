<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/29
 * Time: 16:25
 */

namespace App\Services\Tasks\NotificationTask;


/**
 *
 *
 * OS側 :
 *　他のユーザからコメントされた時
	あなたの投稿に○◯さんがコメントしました
	ユーザー名　-最大文字数16文字

　* -自分がコメントした他人の投稿に、コメント追加がされた場合
　　　あなたがコメントした投稿に対して◯○さんが新しくコメントしました
　　　ユーザー名　-最大文字数16文字
　　　対象となる投稿の商品名を表示（リアクション内容との間は改行）
　　-最大20文字　それ以上の場合は末尾を「…」表示
 *
 * DB :
 * 他のユーザからコメントされた時
	あなたの投稿に○◯さんがコメントしました
	ユーザー名　-最大文字数16文字
 *　-自分がコメントした他人の投稿に、コメント追加がされた場合
　　　あなたがコメントした投稿に対して◯○さんが新しくコメントしました
　　 ユーザー名　-最大文字数16文字
 * 　コメント　　-最大文字数50文字 ユーザー名とコメントの間は改行しない
　   商品名　　　-最大文字数：最大1行



 * Class CommentNotification
 * @package App\Services\Tasks\NotificationTask
 */
class CommentNotification implements Notification
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