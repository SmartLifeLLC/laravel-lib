<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 14:23
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;

class ReactionUpdateJsonView extends JsonResponseView
{
	function createBody()
	{
		// TODO: Implement createBody() method.
		$this->body = [
			'message' => '成功しました。'
		];
	}

}