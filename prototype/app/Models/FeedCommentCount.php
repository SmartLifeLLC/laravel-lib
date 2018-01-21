<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 23:23
 */

namespace App\Models;


use App\Models\Common\DeleteAllForFeedImplements;
use App\Models\Common\DeleteAllForFeedInterface;

class FeedCommentCount extends DBModel implements DeleteAllForFeedInterface
{
	use DeleteAllForFeedImplements;
	/**
	 * @param $feedId
	 */
	public function increaseCommentCount($feedId){
		$entity = $this
			->where('feed_id',$feedId)
			->first();

		//Run create
		if($entity == null){
			//Create entity
			$this->insert(
				[
					'feed_id'=>$feedId,
					'comment_count' => 1
				]
			);

			//Run update
		}else{
			$entity->comment_count += 1;
			$entity->save();
		}
	}


	/**
	 * @param $feedId
	 * @throws \Exception
	 */
	public function decreaseCommentCount($feedId){
		$entity = $this
			->where('feed_id',$feedId)
			->first();

		//Run create
		if($entity == null){
			throw new \Exception("Failed to find comment count for feed id {$feedId}");
		}else{
			$entity->comment_count -= 1;
			$entity->save();
		}
	}
}