<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:39
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
use App\Models\Common\DeleteAllForContributionImplements;
use App\Models\Common\DeleteAllForContributionInterface;

class ContributionReactionNotificationDelivery extends DBModel implements DeleteAllForContributionInterface
{
	use DeleteAllForContributionImplements;

	/**
	 * @param $userId
	 * @param $contributionId
	 * @param $type
	 * @return bool
	 */
	public function isPreviousSent($userId, $contributionId, $type){
		$entity = $this
			->where('user_id',$userId)
			->where('contribution_id',$contributionId)
			->where('contribution_reaction_type',$type)
			->first();

		if($entity == null){
			$this->insert(
				[
					'user_id'=>$userId,
					'contribution_id'=>$contributionId,
					'contribution_reaction_type'=>$type,
					'created_at'=>date(DateTimeFormat::General)
				]
			);
			return false;
		}else{
			return true;
		}

	}

}