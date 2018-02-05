<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:40
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
use App\Constants\ContributionReactionType;
use App\Models\Common\DeleteAllForContributionImplements;
use App\Models\Common\DeleteAllForContributionInterface;
use App\Models\Common\ContributionReactionImplements;
use App\Models\Common\ContributionReactionInterface;
use App\Models\Common\UserContentsCountBuilderImplements;
use App\Models\Common\UserContentsCountBuilderInterface;

class ContributionInterestReaction extends DBModel implements ContributionReactionInterface,DeleteAllForContributionInterface,UserContentsCountBuilderInterface
{
	use ContributionReactionImplements;
	use DeleteAllForContributionImplements;
	use UserContentsCountBuilderImplements;

	public function getReactionType()
	{
		return ContributionReactionType::INTEREST;
	}

	/**
	 * @param $userId
	 * @param $blockList
	 * @return int
	 */
	public function getCountForUser($userId,$blockList):int{
		$queryBuilder = $this->getCountQueryForUser($userId,$blockList,'contributions.user_id');
		return
			$queryBuilder
			->leftJoin('contributions','contributions.id','=','contribution_interest_reactions.contribution_id')
			->count('contribution_interest_reactions.id');
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
                'created_at'=>$created
            ]
        );
    }
}