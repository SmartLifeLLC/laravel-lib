<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 18:01
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;

class ContributionDeleteJsonView extends JsonResponseView
{

	function createBody()
	{
		$this->body =
			[
				'message' =>"投稿が削除されました。",
				'debug' => "Backup record id   : {$this->data}"
			];
	}
}