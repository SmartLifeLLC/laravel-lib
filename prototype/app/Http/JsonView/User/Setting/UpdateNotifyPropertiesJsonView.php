<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/22
 * Time: 3:09
 */

namespace App\Http\JsonView\User\Setting;


use App\Http\JsonView\JsonResponseView;

class UpdateNotifyPropertiesJsonView extends JsonResponseView
{
	function createBody()
	{
		$this->body = ['message' => "設定の編集に成功しました。" ];
	}

}