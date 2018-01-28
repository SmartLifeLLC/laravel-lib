<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:42
 */

namespace App\Models\Common;


use App\Constants\SystemConstants;
use App\Constants\DateTimeFormat;
use App\Constants\DefaultValues;
use App\Constants\ContributionReactionType;
use DB;
trait ContributionReactionImplements
{
	/**
	 * @param $userId
	 * @param $contributionId
	 * @param null $type
	 */
	public function createReaction($userId, $contributionId, $type = null){

		$data = ['user_id'=>$userId,'contribution_id'=>$contributionId,'created_at'=>date(DateTimeFormat::General)];
		if($type != null) $data['contribution_reaction_type'] = $type;
		$this->insert($data);
	}

	/**
	 * @param $userId
	 * @param $contributionId
	 * @param null $type
	 */
	public function deleteReaction($userId, $contributionId, $type = null){
		$queryBuilder =
			$this->where('user_id',$userId)
			 ->where('contribution_id',$contributionId);

		if($type != null) $queryBuilder = $queryBuilder->where('contribution_reaction_type',$type);

		$queryBuilder->delete();
	}


	/**
	 * @param $userId
	 * @param $contributionId
	 * @param null $type
	 * @return mixed
	 */
	public function findReaction($userId, $contributionId, $type = null){
		$queryBuilder =
			$this->where('user_id',$userId)
				->where('contribution_id',$contributionId);
		if($type != null) $queryBuilder = $queryBuilder->where('contribution_reaction_type',$type);

		return $queryBuilder->first();
	}

	/**
	 * @param $userId
	 * @param $contributionId
	 * @param $blockUsers
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getList($userId, $contributionId, $blockUsers, $page, $limit){
		$targetTableName = $this->getTable();
		$type = $this->getReactionType();
		if( $type == ContributionReactionType::ALL){
			$reactionTypeColumn = "contribution_reaction_type";
		}else{
			$reactionTypeColumn = "{$type} as contribution_reaction_type";
		}
		$query =
			$this
			->select(
			"{$targetTableName}.id",
			"users.id as user_id",
			"users.user_name",
			"images.s3_key as profile_image_s3_key",
            DB::raw("{$reactionTypeColumn}") ,
			DB::raw("IFNULL(follows.is_on, 0) AS is_following"),
		    "users.description")
			->leftJoin('users','users.id','=',"{$targetTableName}.user_id")
			->leftJoin('images','users.id','=',"images.user_id")
			->leftJoin('follows',function($join) use ($userId){
				$join
					->on('users.id','=','follows.user_id')
					->where('follows.target_user_id',$userId);})
			->where('contribution_id',$contributionId)
			->orderBy('follows.is_on','DESC')
			->orderBy("{$targetTableName}.created_at","DESC")
			->offset($this->getOffset($limit,$page))
			->limit($limit);

		if(!empty($blockUsers)) {
			$blockUsersString = implode(',',$blockUsers);
			$query->whereRaw(DB::raw("NOT EXISTS(select * from users as block_users where block_users.id in ({$blockUsersString}) and users.id = block_users.id)"));
		}


		return $query->get();
	}

}