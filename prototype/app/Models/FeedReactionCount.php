<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:38
 */

namespace App\Models;


use App\Constants\FeedReactionType;

class FeedReactionCount extends DBModel
{
	/**
	 * @param $productId
	 * @param $feedId
	 * @param $feedType
	 * @throws \Exception
	 */
	public function incrementCount($productId,$feedId,$feedType){
		$entity = $this
			 ->where('feed_id',$feedId)
			 ->where('product_id',$productId)
			 ->first();

		$column = $this->getTargetColumn($feedType);

		//Run create
		if($entity == null){
			//Create entity
			$this->insert(
				[
					'feed_id'=>$feedId,
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
	 * @param $feedId
	 * @param $feedType
	 * @throws \Exception
	 */
	public function decrementCount($productId,$feedId,$feedType){
		$entity = $this
			->where('feed_id',$feedId)
			->where('product_id',$productId)
			->first();
		if($entity == null){
			throw new \Exception("Count data for the feed id  {$feedId}  and product id {$productId} could not be found.");
		}
		$column = $this->getTargetColumn($feedType);
		$entity->{$column} -= 1;
		$entity->total_reaction_count -= 1;
		$entity->save();
	}


	/**
	 * @param int $feedType
	 * @return string
	 * @throws \Exception
	 */
	private function getTargetColumn(int $feedType){
		switch ($feedType){
			case FeedReactionType::LIKE:{
				$column = 'like_reaction_count';
				break;
			}
			case FeedReactionType::INTEREST:{
				$column = 'interest_reaction_count';
				break;
			}
			case FeedReactionType::HAVE:{
				$column = 'have_reaction_count';
				break;
			}
			default :{
				throw new \Exception("Failed to find type for the " . $feedType);
			}
		}
		return $column;
	}


	/**
	 * @param $feedId
	 * @return mixed
	 */
	public function getCountsForFeed($feedId){
		return $this->where('feed_id',$feedId)->first();
	}


}