<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:56
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;

class CommentDeleteJsonView extends JsonResponseView
{
	function createBody()
	{
		$this->body = [
			'review_post_comment_id'=>$this->data['comment_id'],
			'user_id' => $this->data['user_id'],
			'message' => "レビュー投稿コメントの削除が正常に完了しました。"
		];
	}
}