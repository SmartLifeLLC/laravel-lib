<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:38
 */

namespace App\Models;


use App\Constants\FeedReactionType;
use App\Models\Common\FeedReactionImplements;
use App\Models\Common\FeedReactionInterface;

class FeedLikeReaction extends DBModel implements FeedReactionInterface
{
	use FeedReactionImplements;

	public function getReactionType()
	{
		return FeedReactionType::LIKE;
	}
}