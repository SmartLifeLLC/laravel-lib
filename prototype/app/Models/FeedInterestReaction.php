<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:40
 */

namespace App\Models;


use App\Constants\FeedReactionType;
use App\Models\Common\FeedReactionImplements;
use App\Models\Common\FeedReactionInterface;

class FeedInterestReaction extends DBModel implements FeedReactionInterface
{
	use FeedReactionImplements;

	public function getReactionType()
	{
		return FeedReactionType::INTEREST;
	}

	/**
	 * @param $userId
	 * @return int
	 */
	public function getCountForUser($userId):int{
		return self::where('user_id',$userId)->count();
	}
}