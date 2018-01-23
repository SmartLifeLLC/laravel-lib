<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:38
 */

namespace App\Models;


use App\Constants\ContributionReactionType;
use App\Models\Common\DeleteAllForContributionImplements;
use App\Models\Common\DeleteAllForContributionInterface;

class ContributionReactionCount extends DBModel implements DeleteAllForContributionInterface
{
	use DeleteAllForContributionImplements;
	/**
	 * @param $productId
	 * @param $contributionId
	 * @param $contributionType
	 * @throws \Exception
	 */
	public function incrementCount($productId, $contributionId, $contributionType){
		$entity = $this
			 ->where('contribution_id',$contributionId)
			 ->where('product_id',$productId)
			 ->first();

		$column = $this->getTargetColumn($contributionType);

		//Run create
		if($entity == null){
			//Create entity
			$this->insert(
				[
					'contribution_id'=>$contributionId,
					'product_id'=>$productId,
					'total_reaction_count'=> 1,
					$column => 1
				]
			);

		//Run update
		}else{
			$entity->{$column} += 1;
			$entity->total_reaction_count += 1;
			$entity->save();
		}
	}

	/**
	 * @param $productId
	 * @param $contributionId
	 * @param $contributionType
	 * @throws \Exception
	 */
	public function decrementCount($productId, $contributionId, $contributionType){
		$entity = $this
			->where('contribution_id',$contributionId)
			->where('product_id',$productId)
			->first();
		if($entity == null){
			throw new \Exception("Count data for the contribution id  {$contributionId}  and product id {$productId} could not be found.");
		}
		$column = $this->getTargetColumn($contributionType);
		$entity->{$column} -= 1;
		$entity->total_reaction_count -= 1;
		$entity->save();
	}


	/**
	 * @param int $contributionType
	 * @return string
	 * @throws \Exception
	 */
	private function getTargetColumn(int $contributionType){
		switch ($contributionType){
			case ContributionReactionType::LIKE:{
				$column = 'like_reaction_count';
				break;
			}
			case ContributionReactionType::INTEREST:{
				$column = 'interest_reaction_count';
				break;
			}
			case ContributionReactionType::HAVE:{
				$column = 'have_reaction_count';
				break;
			}
			default :{
				throw new \Exception("Failed to find type for the " . $contributionType);
			}
		}
		return $column;
	}


	/**
	 * @param $contributionId
	 * @return mixed
	 */
	public function getCountsForContribution($contributionId){
		return $this->where('contribution_id',$contributionId)->first();
	}

}