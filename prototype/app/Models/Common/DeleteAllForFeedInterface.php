<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 18:19
 */

namespace App\Models\Common;


interface DeleteAllForFeedInterface
{
	/**
	 * @param $feedId
	 * @return mixed
	 */
	public function deleteAllForFeed($feedId);
}