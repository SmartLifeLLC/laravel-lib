<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:42
 */

namespace App\Models\Common;


use App\Constants\ConfigConstants;
use App\Constants\DateTimeFormat;
use App\Constants\DefaultValues;
use App\Constants\FeedReactionType;
use DB;
trait FeedReactionImplements
{
	/**
	 * @param $userId
	 * @param $feedId
	 * @param null $type
	 */
	public function createReaction($userId, $feedId, $type = null){

		$data = ['user_id'=>$userId,'feed_id'=>$feedId,'created_at'=>date(DateTimeFormat::General)];
		if($type != null) $data['feed_reaction_type'] = $type;
		$this->insert($data);
	}

	/**
	 * @param $userId
	 * @param $feedId
	 * @param null $type
	 */
	public function deleteReaction($userId, $feedId, $type = null){
		$queryBuilder =
			$this->where('user_id',$userId)
			 ->where('feed_id',$feedId);

		if($type != null) $queryBuilder = $queryBuilder->where('feed_reaction_type',$type);

		$queryBuilder->delete();
	}

	/**
	 * @param $userId
	 * @param $feedId
	 * @param null $type
	 * @return mixed
	 */
	public function findReaction($userId, $feedId, $type = null){
		$queryBuilder =
			$this->where('user_id',$userId)
				->where('feed_id',$feedId);
		if($type != null) $queryBuilder = $queryBuilder->where('feed_reaction_type',$type);

		return $queryBuilder->first();
	}

	/**
	 * @param $userId
	 * @param $feedId
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getList($userId,$feedId,$page,$limit){
		$targetTableName = $this->getTable();
		$type = $this->getReactionType();
		if( $type == FeedReactionType::ALL){
			$reactionTypeColumn = "feed_reaction_type";
		}else{
			$reactionTypeColumn = "{$type} as feed_reaction_type";
		}


		$query = "
		SELECT
  			users.id,
  			users.user_name,
  			(CASE
    			  WHEN images.s3_key IS NULL THEN \"\"
    				ELSE CONCAT(?,images.s3_key)
  			END) AS profile_image_url,
  			{$reactionTypeColumn} ,
  			IFNULL(follows.is_on, 0) AS is_following,
  			users.description as introduction
		FROM
  			{$targetTableName}
  		LEFT JOIN users ON {$targetTableName}.user_id = users.id
  		LEFT JOIN images ON users.id = images.user_id
  		LEFT JOIN follows ON users.id = follows.user_id AND follows.target_user_id = ?
			WHERE feed_id = ?
		ORDER BY follows.is_on DESC, {$targetTableName}.created_at DESC
		LIMIT ? OFFSET ?;
		";

		$offset = $this->getOffset($limit,$page);
		return DB::select($query,[ConfigConstants::getCdnHost(),$userId,$feedId,$limit,$offset]);
	}

}