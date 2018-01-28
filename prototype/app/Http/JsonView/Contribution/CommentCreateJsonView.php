<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:56
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;

class CommentCreateJsonView extends JsonResponseView
{


	function createBody()
	{
		$this->body = [
			'contribution_comment_id'=>$this->data,
			'message' => "コメント投稿に成功しました。"
		];
	}
}