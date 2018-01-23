<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 4:05
 */

namespace App\Models;


use App\Constants\ContributionFeelingType;
use App\Constants\DateTimeFormat;
use DB;
class Contribution extends DBModel
{
	protected $guarded = [];

	/**
	 * @param $userId
	 * @param $productId
	 * @param $contributionFeelingType
	 * @param $content
	 * @param $imageIds
	 * @return mixed
	 */
	public function createGetId($userId, $productId, $contributionFeelingType, $content, $imageIds){
		$data = [
					'user_id'=>$userId,
					'product_id'=>$productId,
					'feeling'=>$contributionFeelingType,
					'content'=>$content,
					'modified_at'=>date(DateTimeFormat::General)
				];
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
	public function getContributionForUserIdProductId($userId, $productId){
		return $this->where('user_id',$userId)->where('product_id',$productId)->first();
	}

	/**
	 * @param $userId
	 * @param $contributionId
	 * @return mixed
	 */
	public function getContributionForUserIdContributionId($userId, $contributionId){
		return $this->where('user_id',$userId)->where('id',$contributionId)->first();
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
	 * @param $contributionIc
	 * @return mixed
	 */
	public function getDetail($userId, $contributionIc){
		return $this
			->select(
					'contributions.*',
					'contribution_users.*',
					'my_contributions.id as my_contribution_id',
					'contribution_comment_counts.comment_count',
					'contribution_reaction_counts.contribution_id as contribution_id',
					'contribution_reaction_counts.total_reaction_count',
					'contribution_reaction_counts.like_reaction_count',
					'contribution_reaction_counts.interest_reaction_count',
					'contribution_reaction_counts.have_reaction_count',
					'contribution_like_reactions.id as contribution_like_reaction_id',
					'contribution_interest_reactions.id as contribution_interest_reaction_id ',
					'contribution_have_reactions.id as contribution_have_reaction_id ',
					'cover_images.s3_key as cover_image_s3_key',
					'profile_images.s3_key as profile_image_s3_key',
					'follows.id as follow_id')
			->where('contributions.id',$contributionIc)
			->leftJoin('contribution_reaction_counts','contribution_reaction_counts.contribution_id','=','contributions.id')
			->leftJoin('contribution_comment_counts','contribution_comment_counts.contribution_id','=','contributions.id')

			->leftJoin('images as image_0','image_0.id','=','contributions.image_id_0')
			->leftJoin('images as image_1','image_1.id','=','contributions.image_id_1')
			->leftJoin('images as image_2','image_2.id','=','contributions.image_id_2')
			->leftJoin('images as image_3','image_3.id','=','contributions.image_id_3')
			->leftJoin('users as contribution_users','contribution_users.id','=','contributions.user_id')
			->leftJoin('images as cover_images','cover_images.id','=','contribution_users.cover_image_id')
			->leftJoin('images as profile_images','profile_images.id','=','contribution_users.profile_image_id')
			->leftJoin('contributions as my_contributions',function($join) use ($userId,$contributionIc){
				$join->on('my_contributions.user_id','=',DB::raw($userId));
				$join->on('my_contributions.product_id','=','contributions.product_id');})
			->leftJoin('follows',function($join) use ($userId){
				$join->on('follows.user_id','=',DB::raw($userId));
				$join->on('follows.target_user_id','=','contributions.user_id');})
			->leftJoin('contribution_have_reactions',function($join) use ($userId,$contributionIc){
				$join->on('contribution_have_reactions.user_id','=',DB::raw($userId));
				$join->on('contribution_have_reactions.contribution_id','=',DB::raw($contributionIc));})
			->leftJoin('contribution_like_reactions',function($join) use ($userId,$contributionIc){
				$join->on('contribution_like_reactions.user_id','=',DB::raw($userId));
				$join->on('contribution_like_reactions.contribution_id','=',DB::raw($contributionIc));})
			->leftJoin('contribution_interest_reactions',function($join) use ($userId,$contributionIc){
				$join->on('contribution_interest_reactions.user_id','=',DB::raw($userId));
				$join->on('contribution_interest_reactions.contribution_id','=',DB::raw($contributionIc));
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
		$queryBuilder = $this->setListSelectTargets($this);
		$queryBuilder = $this->setListCommonLeftJoin($queryBuilder,$userId);
		$feelingType = mb_strtolower($feelingType);
		if($feelingType != ContributionFeelingType::ALL){
			$queryBuilder->where('contributions.feeling',$feelingType);
		}
		$queryBuilder
			->where('contributions.product_id',$productId)
			->offset($offset)
			->limit($limit);
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
		$queryBuilder = $this->setListSelectTargets($this);
		$queryBuilder = $this->setListCommonLeftJoin($queryBuilder,$userId);
		return $queryBuilder
			->leftJoin(DB::raw('contribution_interest_reactions as owner_interest'),function($join) {
				$join->on('owner_interest.contribution_id','=','contributions.id');})
			->where('owner_interest.user_id',$ownerId)
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
		$queryBuilder = $this->setListSelectTargets($this);
		$queryBuilder = $this->setListCommonLeftJoin($queryBuilder,$userId);
		return $queryBuilder
				->where('contributions.user_id',$ownerId)
				->offset($offset)
				->limit($limit)
				->get();
	}

	/**
	 * @param $userId
	 * @param $contributionOwnerIds
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getListForFeed($userId,$contributionOwnerIds, $page, $limit){

		$offset = $this->getOffset($limit,$page);
		$queryBuilder = $this->setListSelectTargets($this);
		$queryBuilder = $this->setListCommonLeftJoin($queryBuilder,$userId);
		return $queryBuilder
			->whereIn('contributions.user_id',$contributionOwnerIds)
			->offset($offset)
			->limit($limit)
			->get();
	}

	private function setListSelectTargets($queryBuilder){
		return
			$queryBuilder->select(
			'contributions.id as id',
			'contributions.product_id',
			'contributions.feeling',
			'contributions.image_id_0',
			'contributions.image_id_1',
			'contributions.image_id_2',
			'contributions.image_id_3',
			'contributions.content',
			'contributions.created_at as contribution_created_at',
			'contribution_users.user_name',
			'contribution_users.id as contribution_user_id',
			'contribution_users.birthday',
			'contribution_users.user_name',
			'contribution_users.gender',
			'contribution_users.gender_published_flag',
			'contribution_users.birthday_published_flag',
			'contribution_reaction_counts.total_reaction_count',
			'contribution_reaction_counts.have_reaction_count',
			'contribution_reaction_counts.like_reaction_count',
			'contribution_reaction_counts.interest_reaction_count',
			'product_contribution_counts.contribution_count',
			'product_contribution_counts.positive_count',
			'product_contribution_counts.negative_count',
			'my_contributions.id as my_contribution_id',
			'contribution_comment_counts.comment_count',
			'contribution_reaction_counts.contribution_id as contribution_id',
			'contribution_like_reactions.id as contribution_like_reaction_id',
			'contribution_interest_reactions.id as contribution_interest_reaction_id ',
			'contribution_have_reactions.id as contribution_have_reaction_id ',
			'cover_images.s3_key as cover_image_s3_key',
			'profile_images.s3_key as profile_image_s3_key',
			'follows.id as follow_id');
	}

	private function setListCommonLeftJoin($queryBuilder,$userId){

		return $queryBuilder
			->leftJoin('products','products.id','=','contributions.product_id')
			->leftJoin('product_contribution_counts','product_contribution_counts.product_id','=','products.id')
			->leftJoin('contribution_reaction_counts','contribution_reaction_counts.contribution_id','=','contributions.id')
			->leftJoin('contribution_comment_counts','contribution_comment_counts.contribution_id','=','contributions.id')
			->leftJoin('images as image_0','image_0.id','=','contributions.image_id_0')
			->leftJoin('images as image_1','image_1.id','=','contributions.image_id_1')
			->leftJoin('images as image_2','image_2.id','=','contributions.image_id_2')
			->leftJoin('images as image_3','image_3.id','=','contributions.image_id_3')
			->leftJoin('users as contribution_users','contribution_users.id','=','contributions.user_id')
			->leftJoin('images as cover_images','cover_images.id','=','contribution_users.cover_image_id')
			->leftJoin('images as profile_images','profile_images.id','=','contribution_users.profile_image_id')
			->leftJoin('contributions as my_contributions',function($join) use ($userId){
				$join->on('my_contributions.user_id','=',DB::raw($userId));
				$join->on('my_contributions.product_id','=','contributions.product_id');})
			->leftJoin('follows',function($join) use ($userId){
				$join->on('follows.user_id','=',DB::raw($userId));
				$join->on('follows.target_user_id','=','contributions.user_id');})
			->leftJoin('contribution_have_reactions',function($join) use ($userId){
				$join->on('contribution_have_reactions.user_id','=',DB::raw($userId));
				$join->on('contribution_have_reactions.contribution_id','=','contributions.id');})
			->leftJoin('contribution_like_reactions',function($join) use ($userId){
				$join->on('contribution_like_reactions.user_id','=',DB::raw($userId));
				$join->on('contribution_like_reactions.contribution_id','=','contributions.id');})
			->leftJoin('contribution_interest_reactions',function($join) use ($userId){
				$join->on('contribution_interest_reactions.user_id','=',DB::raw($userId));
				$join->on('contribution_interest_reactions.contribution_id','=','contributions.id');
			});

	}
}