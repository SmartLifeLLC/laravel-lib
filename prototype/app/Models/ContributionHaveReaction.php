<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:39
 */

namespace App\Models;


use App\Constants\ContributionReactionType;
use App\Models\Common\DeleteAllForContributionImplements;
use App\Models\Common\DeleteAllForContributionInterface;
use App\Models\Common\ContributionReactionInterface;
use App\Models\Common\ContributionReactionImplements;
class ContributionHaveReaction extends DBModel implements ContributionReactionInterface, DeleteAllForContributionInterface
{
	use ContributionReactionImplements;
	use DeleteAllForContributionImplements;

	public function getReactionType()
	{
	   return ContributionReactionType::HAVE;
	}


}