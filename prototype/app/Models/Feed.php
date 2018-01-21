<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 4:05
 */

namespace App\Models;


use App\Constants\FeedFeelingType;
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
					'feed_comment_counts.comment_count',
					'feed_reaction_counts.feed_id as feed_id',
					'feed_reaction_counts.total_reaction_count',
					'feed_reaction_counts.like_reaction_count',
					'feed_reaction_counts.interest_reaction_count',
					'feed_reaction_counts.have_reaction_count',
					'feed_like_reactions.id as feed_like_reaction_id',
					'feed_interest_reactions.id as feed_interest_reaction_id ',
					'feed_have_reactions.id as feed_have_reaction_id ',
					'cover_images.s3_key as cover_image_s3_key',
					'profile_images.s3_key as profile_image_s3_key',
					'follows.id as follow_id')
			->where('feeds.id',$feedId)
			->leftJoin('feed_reaction_counts','feed_reaction_counts.feed_id','=','feeds.id')
			->leftJoin('feed_comment_counts','feed_comment_counts.feed_id','=','feeds.id')

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

	/**
	 * @param $userId
	 * @param $productId
	 * @param $feelingType
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getListForProduct($userId, $productId, $feelingType, $page, $limit){
		$offset = $this->getOffset($limit,$page);
		$queryBuilder =
			$this
			->select(
				'feeds.*',
				'feed_users.*',
				'my_feeds.id as my_feed_id',
				'feed_comment_counts.comment_count',
				'feed_reaction_counts.feed_id as feed_id',
				'feed_like_reactions.id as feed_like_reaction_id',
				'feed_interest_reactions.id as feed_interest_reaction_id ',
				'feed_have_reactions.id as feed_have_reaction_id ',
				'cover_images.s3_key as cover_image_s3_key',
				'profile_images.s3_key as profile_image_s3_key',
				'follows.id as follow_id')
			->where('feeds.product_id',$productId)
			->leftJoin('products','products.id','=','feeds.product_id')
			->leftJoin('product_feed_counts','product_feed_counts.product_id','=','products.id')
			->leftJoin('feed_reaction_counts','feed_reaction_counts.feed_id','=','feeds.id')
			->leftJoin('feed_comment_counts','feed_comment_counts.feed_id','=','feeds.id')
			->leftJoin('images as image_0','image_0.id','=','feeds.image_id_0')
			->leftJoin('images as image_1','image_1.id','=','feeds.image_id_1')
			->leftJoin('images as image_2','image_2.id','=','feeds.image_id_2')
			->leftJoin('images as image_3','image_3.id','=','feeds.image_id_3')
			->leftJoin('users as feed_users','feed_users.id','=','feeds.user_id')
			->leftJoin('images as cover_images','cover_images.id','=','feed_users.cover_image_id')
			->leftJoin('images as profile_images','profile_images.id','=','feed_users.profile_image_id')
			->leftJoin('feeds as my_feeds',function($join) use ($userId,$productId){
				$join->on('my_feeds.user_id','=',DB::raw($userId));
				$join->on('my_feeds.product_id','=','feeds.product_id');})
			->leftJoin('follows',function($join) use ($userId){
				$join->on('follows.user_id','=',DB::raw($userId));
				$join->on('follows.target_user_id','=','feeds.user_id');})
			->leftJoin('feed_have_reactions',function($join) use ($userId){
				$join->on('feed_have_reactions.user_id','=',DB::raw($userId));
				$join->on('feed_have_reactions.feed_id','=','feeds.id');})
			->leftJoin('feed_like_reactions',function($join) use ($userId){
				$join->on('feed_like_reactions.user_id','=',DB::raw($userId));
				$join->on('feed_like_reactions.feed_id','=','feeds.id');})
			->leftJoin('feed_interest_reactions',function($join) use ($userId){
				$join->on('feed_interest_reactions.user_id','=',DB::raw($userId));
				$join->on('feed_interest_reactions.feed_id','=','feeds.id');
			})
			->offset($offset)
			->limit($limit);

			$feelingType = mb_strtolower($feelingType);
			if($feelingType != FeedFeelingType::ALL){
				$queryBuilder->where('feeds.feeling',$feelingType);
			}

			return $queryBuilder->get();
	}

	/**
	 * @param $userId
	 * @param $ownerId
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getListForOwnerInterest($userId, $ownerId, $page, $limit){
		$offset = $this->getOffset($limit,$page);
		return
			$this
				->select(
					'feeds.*',
					'feed_users.*',
					'feed_reaction_counts.*',
					'my_feeds.id as my_feed_id',
					'feed_comment_counts.comment_count',
					'feed_reaction_counts.feed_id as feed_id',
					'feed_like_reactions.id as feed_like_reaction_id',
					'feed_interest_reactions.id as feed_interest_reaction_id ',
					'feed_have_reactions.id as feed_have_reaction_id ',
					'cover_images.s3_key as cover_image_s3_key',
					'profile_images.s3_key as profile_image_s3_key',
					'follows.id as follow_id')
				->where('owner_interest.user_id',$ownerId)
				->leftJoin(DB::raw('feed_interest_reactions as owner_interest'),function($join) {
					$join->on('owner_interest.feed_id','=','feeds.id');})
				->leftJoin('products','products.id','=','feeds.product_id')
				->leftJoin('product_feed_counts','product_feed_counts.product_id','=','products.id')
				->leftJoin('feed_reaction_counts','feed_reaction_counts.feed_id','=','feeds.id')
				->leftJoin('feed_comment_counts','feed_comment_counts.feed_id','=','feeds.id')
				->leftJoin('images as image_0','image_0.id','=','feeds.image_id_0')
				->leftJoin('images as image_1','image_1.id','=','feeds.image_id_1')
				->leftJoin('images as image_2','image_2.id','=','feeds.image_id_2')
				->leftJoin('images as image_3','image_3.id','=','feeds.image_id_3')
				->leftJoin('users as feed_users','feed_users.id','=','feeds.user_id')
				->leftJoin('images as cover_images','cover_images.id','=','feed_users.cover_image_id')
				->leftJoin('images as profile_images','profile_images.id','=','feed_users.profile_image_id')
				->leftJoin('feeds as my_feeds',function($join) use ($userId){
					$join->on('my_feeds.user_id','=',DB::raw($userId));
					$join->on('my_feeds.product_id','=','feeds.product_id');})
				->leftJoin('follows',function($join) use ($userId){
					$join->on('follows.user_id','=',DB::raw($userId));
					$join->on('follows.target_user_id','=','feeds.user_id');})
				->leftJoin('feed_have_reactions',function($join) use ($userId){
					$join->on('feed_have_reactions.user_id','=',DB::raw($userId));
					$join->on('feed_have_reactions.feed_id','=','feeds.id');})
				->leftJoin('feed_like_reactions',function($join) use ($userId){
					$join->on('feed_like_reactions.user_id','=',DB::raw($userId));
					$join->on('feed_like_reactions.feed_id','=','feeds.id');})
				->leftJoin('feed_interest_reactions',function($join) use ($userId){
					$join->on('feed_interest_reactions.user_id','=',DB::raw($userId));
					$join->on('feed_interest_reactions.feed_id','=','feeds.id');
				})
				->offset($offset)
				->limit($limit)
				->get();
	}

	/**
	 * @param $userId
	 * @param $ownerId
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getListForOwner($userId, $ownerId, $page, $limit){
		$offset = $this->getOffset($limit,$page);
		return
			$this
				->select(
					'feeds.id as id',
					'feeds.product_id',
					'feeds.feeling',
					'feeds.image_id_0',
					'feeds.image_id_1',
					'feeds.image_id_2',
					'feeds.image_id_3',
					'feeds.content',
					'feeds.created_at as feed_created_at',
					'feed_users.user_name',
					'feed_users.id as feed_user_id',
					'feed_users.birthday',
					'feed_users.user_name',
					'feed_users.gender',
					'feed_users.gender_published_flag',
					'feed_users.birthday_published_flag',
					'feed_reaction_counts.total_reaction_count',
					'feed_reaction_counts.have_reaction_count',
					'feed_reaction_counts.like_reaction_count',
					'feed_reaction_counts.interest_reaction_count',
					'my_feeds.id as my_feed_id',
					'feed_comment_counts.comment_count',
					'feed_reaction_counts.feed_id as feed_id',
					'feed_like_reactions.id as feed_like_reaction_id',
					'feed_interest_reactions.id as feed_interest_reaction_id ',
					'feed_have_reactions.id as feed_have_reaction_id ',
					'cover_images.s3_key as cover_image_s3_key',
					'profile_images.s3_key as profile_image_s3_key',
					'follows.id as follow_id')
				->where('feeds.user_id',$ownerId)
				->leftJoin('products','products.id','=','feeds.product_id')
				->leftJoin('product_feed_counts','product_feed_counts.product_id','=','products.id')
				->leftJoin('feed_reaction_counts','feed_reaction_counts.feed_id','=','feeds.id')
				->leftJoin('feed_comment_counts','feed_comment_counts.feed_id','=','feeds.id')
				->leftJoin('images as image_0','image_0.id','=','feeds.image_id_0')
				->leftJoin('images as image_1','image_1.id','=','feeds.image_id_1')
				->leftJoin('images as image_2','image_2.id','=','feeds.image_id_2')
				->leftJoin('images as image_3','image_3.id','=','feeds.image_id_3')
				->leftJoin('users as feed_users','feed_users.id','=','feeds.user_id')
				->leftJoin('images as cover_images','cover_images.id','=','feed_users.cover_image_id')
				->leftJoin('images as profile_images','profile_images.id','=','feed_users.profile_image_id')
				->leftJoin('feeds as my_feeds',function($join) use ($userId){
					$join->on('my_feeds.user_id','=',DB::raw($userId));
					$join->on('my_feeds.product_id','=','feeds.product_id');})
				->leftJoin('follows',function($join) use ($userId){
					$join->on('follows.user_id','=',DB::raw($userId));
					$join->on('follows.target_user_id','=','feeds.user_id');})
				->leftJoin('feed_have_reactions',function($join) use ($userId){
					$join->on('feed_have_reactions.user_id','=',DB::raw($userId));
					$join->on('feed_have_reactions.feed_id','=','feeds.id');})
				->leftJoin('feed_like_reactions',function($join) use ($userId){
					$join->on('feed_like_reactions.user_id','=',DB::raw($userId));
					$join->on('feed_like_reactions.feed_id','=','feeds.id');})
				->leftJoin('feed_interest_reactions',function($join) use ($userId){
					$join->on('feed_interest_reactions.user_id','=',DB::raw($userId));
					$join->on('feed_interest_reactions.feed_id','=','feeds.id');
				})
				->offset($offset)
				->limit($limit)
				->get();
	}
}