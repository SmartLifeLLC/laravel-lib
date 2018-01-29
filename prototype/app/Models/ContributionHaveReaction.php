<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:39
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
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

    /**
     * @param $userId
     * @param $contributionId
     * @param $created
     * @return mixed
     */
    public function translateGetId($userId, $contributionId, $created){
        return $this->insertGetId(
            [
                'user_id'=>$userId,
                'contribution_id'=>$contributionId,
                'created_at'=>$created,
                'updated_at'=>date(DateTimeFormat::General)

            ]
        );
    }
}