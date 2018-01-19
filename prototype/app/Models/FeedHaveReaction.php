<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:39
 */

namespace App\Models;


use App\Constants\FeedReactionType;
use App\Models\Common\FeedReactionInterface;
use App\Models\Common\FeedReactionImplements;
class FeedHaveReaction extends DBModel implements FeedReactionInterface
{
	use FeedReactionImplements;

	public function getReactionType()
	{
	   return FeedReactionType::HAVE;
	}


}