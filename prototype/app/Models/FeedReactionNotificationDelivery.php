<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:39
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
use App\Models\Common\DeleteAllForFeedImplements;
use App\Models\Common\DeleteAllForFeedInterface;

class FeedReactionNotificationDelivery extends DBModel implements DeleteAllForFeedInterface
{
	use DeleteAllForFeedImplements;

	/**
	 * @param $userId
	 * @param $feedId
	 * @param $type
	 * @return bool
	 */
	public function isPreviousSent($userId,$feedId,$type){
		$entity = $this
			->where('user_id',$userId)
			->where('feed_id',$feedId)
			->where('feed_reaction_type',$type)
			->first();

		if($entity == null){
			$this->insert(
				[
					'user_id'=>$userId,
					'feed_id'=>$feedId,
					'feed_reaction_type'=>$type,
					'created_at'=>date(DateTimeFormat::General)
				]
			);
			return false;
		}else{
			return true;
		}

	}

}