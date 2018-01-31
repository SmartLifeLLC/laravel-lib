<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/29
 * Time: 16:23
 */

namespace App\Factory;


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
use App\Constants\DefaultValues;
use App\Constants\NotificationLogType;
use App\Constants\NotificationString;
use App\Lib\Util;
use App\Services\Tasks\NotificationTask\NotificationTask;

class CommentNotificationFactory extends NotificationFactory
{
	private $productName;

	/**
	 * @return string
	 */
	static function getNotificationAllowColumn():string
	{
		return "is_comment_notification_allowed";
	}


	/**
	 * @param mixed $productName
	 * @return CommentNotificationFactory
	 */
	public function setProductName($productName):NotificationFactory
	{
		$this->productName = $productName;
		return $this;
	}




	function create(): NotificationTask
	{
		//Comment
		//1.$message
		$name = Util::getStringWithMaxLength($this->userName,DefaultValues::MAX_LENGTH_NOTIFICATION_USERNAME);
		$productName = Util::getStringWithMaxLength($this->productName, DefaultValues::MAX_LENGTH_NOTIFICATION_PRODUCT);
		$message = NotificationString::getComment($name,$productName);
		return
			new NotificationTask($this->fromUserId,$this->targetUsers,$this->contributionId,$this->contributionCommentId,NotificationLogType::COMMENT,$message);
	}

}