<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 19:39
 */

namespace App\Http\JsonView\User\User;


use App\Http\JsonView\JsonResponseView;

class UserEditJsonView extends JsonResponseView
{

	/**
	 * @var user id - See UserService@edit
	 */
	protected $data;

	function createBody()
	{
		$this->body =
			[
				'user_id' => $this->data,
				'message' => "ユーザー編集に成功しました。"
			];
	}

}