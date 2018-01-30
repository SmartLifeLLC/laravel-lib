<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 23:23
 */

namespace App\Models;


use App\Models\Common\DeleteAllForContributionImplements;
use App\Models\Common\DeleteAllForContributionInterface;

class ContributionCommentCount extends DBModel implements DeleteAllForContributionInterface
{
	use DeleteAllForContributionImplements;
	/**
	 * @param $contributionId
	 */
	public function increaseCommentCount($contributionId){
		$entity = $this
			->where('contribution_id',$contributionId)
			->first();

		//Run create
		if($entity == null){
			//Create entity
			$this->insert(
				[
					'contribution_id'=>$contributionId,
					'comment_count' => 1
				]
			);

			//Run update
		}else{
			$this->increment('comment_count');
//			$entity->comment_count += 1;
//			$entity->save();
		}
	}


	/**
	 * @param $contributionId
	 * @throws \Exception
	 */
	public function decreaseCommentCount($contributionId){
		$entity = $this
			->where('contribution_id',$contributionId)
			->first();

		//Run create
		if($entity == null){
			throw new \Exception("Failed to find comment count for contribution id {$contributionId}");
		}else{
			$entity->comment_count -= 1;
			$entity->save();
		}
	}
}