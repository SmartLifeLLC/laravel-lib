<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 6:52
 */

namespace App\Http\JsonView\Contribution;


use App\Constants\StatusMessage;
use App\Http\JsonView\JsonResponseView;

class ContributionCreateJsonView extends JsonResponseView
{


	function createBody()
	{
		$this->body = [
			'review_post_id'=>$this->data,
			'message' => "レビュー投稿に成功しました。"
		];
	}
}