<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 18:19
 */

namespace App\Models\Common;


trait DeleteAllForFeedImplements
{
	/**
	 * @param $feedId
	 * @return mixed
	 */
	public function deleteAllForFeed($feedId){
		return $this->where('feed_id',$feedId)->delete();
	}
}
