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
use DB;

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
	 * @param array $blockUsers
	 * @return mixed
	 */
	public function getCountsForContribution($contributionId,$blockUsers = []){

		if(empty($blockUsers)) {
			$result =
				$this
					->select(
						'contribution_reaction_counts.contribution_id',
						'contribution_reaction_counts.total_reaction_count',
						'contribution_reaction_counts.like_reaction_count',
						'contribution_reaction_counts.interest_reaction_count',
						'contribution_reaction_counts.have_reaction_count',
						DB::raw('0 as block_reaction_like_count'),
						DB::raw('0 as block_reaction_interest_count')
						)
					->where('contribution_id', $contributionId)
					->first();
		}else{
			$blockUserString = implode(',',$blockUsers);
			$result =
				$this
					-> select(
						'contribution_reaction_counts.contribution_id',
						'contribution_reaction_counts.total_reaction_count',
						'contribution_reaction_counts.like_reaction_count',
						'contribution_reaction_counts.interest_reaction_count',
						'contribution_reaction_counts.have_reaction_count',
						DB::raw("(SELECT count(contribution_like_reactions.contribution_id) 
									FROM `contribution_like_reactions` 
									WHERE contribution_id = {$contributionId} and user_id in ({$blockUserString}) 
									GROUP BY contribution_like_reactions.contribution_id limit 1)  as block_reaction_like_count"),

					    DB::raw(
					    	    "(SELECT count(contribution_interest_reactions.contribution_id)
                                    FROM `contribution_interest_reactions`
                                    WHERE contribution_id = {$contributionId} AND user_id IN ({$blockUserString})
                                    GROUP BY contribution_interest_reactions.contribution_id
                                    LIMIT 1)  AS block_reaction_interest_count"))
					->where('contribution_reaction_counts.contribution_id',$contributionId)
					->first();
		}

		if(empty($result))  return $result;
		$result['total_reaction_count'] -= ($result['block_reaction_like_count'] + $result['block_reaction_interest_count'] );
		$result['like_reaction_count'] -= $result['block_reaction_like_count'];
		$result['interest_reaction_count'] -= $result['block_reaction_interest_count'];
		unset($result['block_reaction_like_count']);
		unset($result['block_reaction_interest_count']);
		return $result;
	}

}