<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 14:34
 */

namespace App\Models;



use App\Constants\DateTimeFormat;
use App\Constants\FeedReactionType;
use App\Models\Common\DeleteAllForFeedImplements;
use App\Models\Common\DeleteAllForFeedInterface;
use App\Models\Common\FeedReactionImplements;
use App\Models\Common\FeedReactionInterface;

class FeedAllReaction extends DBModel implements FeedReactionInterface, DeleteAllForFeedInterface
{
	use FeedReactionImplements;
	use DeleteAllForFeedImplements;

	public function getReactionType()
	{
		return FeedReactionType::ALL;
	}
}