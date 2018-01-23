<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:40
 */

namespace App\Models;


use App\Constants\ContributionReactionType;
use App\Models\Common\DeleteAllForContributionImplements;
use App\Models\Common\DeleteAllForContributionInterface;
use App\Models\Common\ContributionReactionImplements;
use App\Models\Common\ContributionReactionInterface;

class ContributionInterestReaction extends DBModel implements ContributionReactionInterface,DeleteAllForContributionInterface
{
	use ContributionReactionImplements;
	use DeleteAllForContributionImplements;

	public function getReactionType()
	{
		return ContributionReactionType::INTEREST;
	}

	/**
	 * @param $userId
	 * @return int
	 */
	public function getCountForUser($userId):int{
		return self::where('user_id',$userId)->count();
	}
}