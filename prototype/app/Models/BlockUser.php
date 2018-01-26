<?php

namespace App\Models;

use App\Constants\DefaultValues;
use App\Constants\SystemConstants;
use Illuminate\Database\Eloquent\Model;
use DB;
class BlockUser extends DBModel
{
    protected $guarded = [];


    /**
     * @param $userId
     * @param $targetUserId
     * @return bool|null
     * @throws \Exception
     */
    public function deleteBlock($userId,$targetUserId){
        return self::where(['user_id'=>$userId,'target_user_id'=>$targetUserId])->delete();
    }

    /**
     * @param $userId
     * @param $targetUserId
     * @return Model
     */
    public function createBlock($userId,$targetUserId){
        return self::updateOrCreate(
        //Conditions
            [
                'user_id'=>$userId,
                'target_user_id'=>$targetUserId],
            //Update value
            [
                'user_id'=>$userId,
                'target_user_id'=>$targetUserId]
        );



    }


    /**
     * @param $userId
     * @param $targetUserId
     * @return bool
     */
    public function isBlockStatus($userId,$targetUserId){
        $result = self::
        where(
                [
                    ['user_id',$userId],
                    ['target_user_id',$targetUserId]
                ])->
        orWhere(
                [
                    ['target_user_id',$userId],
                    ['user_id',$targetUserId]])->get();
        return !$result->isEmpty();
    }

	/**
	 * @param $userId
	 * @param null $ownerId
	 * @return array
	 */
	public function getBlockAndBockedUsers($userId,$ownerId = null){
		$query =
			"SELECT `target_user_id` AS `user_id`
				FROM `block_users`
				WHERE `user_id` = {$userId}
				UNION
				SELECT `target_user_id` AS `user_id`
				FROM `blocked_users`
				WHERE `user_id` = {$userId}";

		if($ownerId != null && $userId != $ownerId){
			$query.= " UNION
				SELECT `target_user_id` AS `user_id`
				FROM `block_users`
				WHERE `user_id` = {$ownerId}
				UNION SELECT `target_user_id` AS `user_id`
				      FROM `blocked_users`
				      WHERE `user_id` = {$ownerId};";


		}
		$blockAndBlockedUsers = DB::select($query,[]);
		if(empty($blockAndBlockedUsers))
			return [];
		return $blockAndBlockedUsers;
	}

	/**
	 * @param $userId => 現在アプリ使用中のユーザ
	 * @param $query
	 * @param null $ownerId => アプリ上表示されているページのオナー（マイページなど
	 * @param string $column
	 * @return mixed
	 */
    public function setWhereNotInBlockUserIds($userId, $query,$ownerId = null,$column="user_id") {
        $blockList = $this->getBlockAndBlockedUserIds($userId);
        if(empty($blockList))
            return $query;

        $query->whereNotIn($column,$blockList);
        return $query;
    }


    /**
     * このメッソドはユーザが自分のブロックユーザリストを見るためのもの。
     * 他人のブロックリストをみることはできないので ownerIdの実装はない。
     * @param $userId
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getBlockUsers($userId, $page = DefaultValues::QUERY_DEFAULT_PAGE, $limit = DefaultValues::QUERY_DEFAULT_LIMIT){

		$offset = $this->getOffset($limit,$page);
		return
			$this
			->select(
				'users.id as user_id',
				'users.description',
				'images.s3_key as profile_image_s3_key',
				'users.user_name'
			)
			->where('block_users.user_id',$userId)
			->leftJoin('users','users.id','=','block_users.target_user_id')
			->leftJoin('images','images.id','=','users.profile_image_id')
			->offset($offset)
		    ->limit($limit)
			->get();
    }

	/**
	 * @param $userId
	 * @param null $ownerId
	 * @return array
	 */
	public function getBlockAndBlockedUserIds($userId,$ownerId = null){
		$blockUsers =  $this->getBlockAndBockedUsers($userId,$ownerId);
		if(empty($blockUsers)) return [];

		//Make id array
		$arrayData = $this->getArrayWithoutKey($blockUsers,'user_id');
		return $arrayData;

	}
}
