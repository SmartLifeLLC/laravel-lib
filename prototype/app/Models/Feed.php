<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 4:05
 */

namespace App\Models;


use DB;
class Feed extends DBModel
{
	protected $guarded = [];

	/**
	 * @param $userId
	 * @param $productId
	 * @param $feedFeelingType
	 * @param $content
	 * @param $imageIds
	 * @return mixed
	 */
	public function createGetId($userId,$productId,$feedFeelingType,$content,$imageIds){
		$data = ['user_id'=>$userId,'product_id'=>$productId,'feeling'=>$feedFeelingType,'content'=>$content];
		for($i = 0 ; $i < count($imageIds) ; $i ++){
			$data['image_id_'.$i] = $imageIds[$i];
		}
		return $this->insertGetId($data);
	}

	/**
	 * @param $userId
	 * @param $productId
	 * @return mixed
	 */
	public function getFeedForUserIdProductId($userId,$productId){
		return $this->where('user_id',$userId)->where('product_id',$productId)->first();
	}

	/**
	 * @param $userId
	 * @param $feedId
	 * @return mixed
	 */
	public function getFeedForUserIdFeedId($userId,$feedId){
		return $this->where('user_id',$userId)->where('id',$feedId)->first();
	}

	/**
	 * @param $userId
	 * @return int
	 */
	public function getCountForUser($userId):int{
		return $this->where('user_id',$userId)->count();
	}

	/**
	 * @param $userId
	 * @param $feedId
	 * @return mixed
	 */
	public function getDetail($userId,$feedId){
		return $this
			->select(
					'feeds.*',
					'feed_users.*',
					'my_feeds.id as my_feed_id',
					'feed_reaction_counts.feed_id as feed_id',
					'feed_like_reactions.id as feed_like_reaction_id',
					'feed_interest_reactions.id as feed_interest_reaction_id ',
					'feed_have_reactions.id as feed_have_reaction_id ',
					'cover_images.s3_key as cover_image_s3_key',
					'profile_images.s3_key as profile_image_s3_key',
					'follows.id as follow_id')
			->where('feeds.id',$feedId)
			->leftJoin('feed_reaction_counts','feed_reaction_counts.feed_id','=','feeds.id')
			->leftJoin('images as image_0','image_0.id','=','feeds.image_id_0')
			->leftJoin('images as image_1','image_1.id','=','feeds.image_id_1')
			->leftJoin('images as image_2','image_2.id','=','feeds.image_id_2')
			->leftJoin('images as image_3','image_3.id','=','feeds.image_id_3')
			->leftJoin('users as feed_users','feed_users.id','=','feeds.user_id')
			->leftJoin('images as cover_images','cover_images.id','=','feed_users.cover_image_id')
			->leftJoin('images as profile_images','profile_images.id','=','feed_users.profile_image_id')
			->leftJoin('feeds as my_feeds',function($join) use ($userId,$feedId){
				$join->on('my_feeds.user_id','=',DB::raw($userId));
				$join->on('my_feeds.product_id','=','feeds.product_id');})
			->leftJoin('follows',function($join) use ($userId){
				$join->on('follows.user_id','=',DB::raw($userId));
				$join->on('follows.target_user_id','=','feeds.user_id');})
			->leftJoin('feed_have_reactions',function($join) use ($userId,$feedId){
				$join->on('feed_have_reactions.user_id','=',DB::raw($userId));
				$join->on('feed_have_reactions.feed_id','=',DB::raw($feedId));})
			->leftJoin('feed_like_reactions',function($join) use ($userId,$feedId){
				$join->on('feed_like_reactions.user_id','=',DB::raw($userId));
				$join->on('feed_like_reactions.feed_id','=',DB::raw($feedId));})
			->leftJoin('feed_interest_reactions',function($join) use ($userId,$feedId){
				$join->on('feed_interest_reactions.user_id','=',DB::raw($userId));
				$join->on('feed_interest_reactions.feed_id','=',DB::raw($feedId));
			})
			->first();
	}






}