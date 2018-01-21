<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 14:34
 */

namespace App\Models;



use App\Constants\DateTimeFormat;
use App\Constants\FeedReactionType;
use App\Models\Common\DeleteAllForFeedImplements;
use App\Models\Common\DeleteAllForFeedInterface;
use App\Models\Common\FeedReactionImplements;
use App\Models\Common\FeedReactionInterface;
use DB;
use Mockery\Exception;

class FeedAllReaction extends DBModel implements FeedReactionInterface, DeleteAllForFeedInterface
{
	use FeedReactionImplements;
	use DeleteAllForFeedImplements;

	public function getReactionType()
	{
		return FeedReactionType::ALL;
	}

	/**
	 * @param $userId
	 * @return mixed
	 */
	public function getReactionCountsForUser($userId){
		$result =
			$this->select(DB::raw('count(id) as count,feed_reaction_type'))
			->where('user_id',$userId)
			->groupBy('feed_reaction_type')
			->orderBy('feed_reaction_type','asc')
			->get();

		$counts = ['like'=>0,'interest'=>0,'have'=>0];

		foreach ($result as $countData){
			$type = $countData['feed_reaction_type'];
			$count =  $countData['count'];
			switch ($type){
				case FeedReactionType::LIKE:
					$counts['like'] = $count;
					break;

				case FeedReactionType::INTEREST:
					$counts['interest'] = $count;
					break;
				case FeedReactionType::HAVE:
					$counts['have'] = $count;
					break;
				default:
					throw new Exception("Failed to find type for type id {$type}");
					break;
			}
		}
		return $counts;
	}

}