<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 14:34
 */

namespace App\Models;



use App\Constants\DateTimeFormat;
use App\Constants\ContributionReactionType;
use App\Models\Common\DeleteAllForContributionImplements;
use App\Models\Common\DeleteAllForContributionInterface;
use App\Models\Common\ContributionReactionImplements;
use App\Models\Common\ContributionReactionInterface;
use DB;
use Mockery\Exception;

class ContributionAllReaction extends DBModel implements ContributionReactionInterface, DeleteAllForContributionInterface
{
	use ContributionReactionImplements;
	use DeleteAllForContributionImplements;

	public function getReactionType()
	{
		return ContributionReactionType::ALL;
	}

	/**
	 * @param $userId
	 * @return mixed
	 */
	public function getReactionCountsForUser($userId){
		$result =
			$this->select(DB::raw('count(id) as count,contribution_reaction_type'))
			->where('user_id',$userId)
			->groupBy('contribution_reaction_type')
			->orderBy('contribution_reaction_type','asc')
			->get();

		$counts = ['like'=>0,'interest'=>0,'have'=>0];

		foreach ($result as $countData){
			$type = $countData['contribution_reaction_type'];
			$count =  $countData['count'];
			switch ($type){
				case ContributionReactionType::LIKE:
					$counts['like'] = $count;
					break;

				case ContributionReactionType::INTEREST:
					$counts['interest'] = $count;
					break;
				case ContributionReactionType::HAVE:
					$counts['have'] = $count;
					break;
				default:
					throw new Exception("Failed to find type for type id {$type}");
					break;
			}
		}
		return $counts;
	}

<<<<<<< HEAD
    /**
     * @param $userId
     * @param $contributionId
     * @param $reactionType
     * @param $created
     * @return mixed
     */
    public function translateGetId($userId, $contributionId, $reactionType, $created){
        return $this->insertGetId(
            [
                'user_id'=>$userId,
                'contribution_id'=>$contributionId,
                'contribution_reaction_type'=>$reactionType,
                'created_at'=>$created,
                'updated_at'=>date(DateTimeFormat::General)

            ]
        );
    }
=======
	/**
	 * @param $contributionIds
	 * @param $userIds
	 * @return mixed
	 */
	public function getAllReactionListForContributionsAndUsers($contributionIds,$userIds){
		$result =
			$this
				->whereIn('contribution_id',$contributionIds)
				->whereIn('user_id',$userIds)
				->get();
		return $result;
	}

>>>>>>> master
}