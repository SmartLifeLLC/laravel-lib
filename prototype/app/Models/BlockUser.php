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
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getBlockAndBockedUsers($userId){
        $result = self::where('user_id',$userId)->
                        orWhere('target_user_id',$userId)->get();
        $blockList = [];
        if($result->isEmpty())
            return $blockList;

        foreach($result as $row){
            if($row->user_id!=$userId)
                $blockList[]=$row->user_id;
            if($row->target_user_id!=$userId)
                $blockList[]=$row->target_user_id;
        }
        $blockList = array_unique($blockList);
        return $blockList;
    }

    //
    /*
     * QueryBuilderにブロックユーザーの情報を付与する
     * 1. 自分がブロックしているユーザー(見たくない)
     * 2. 自分をブロックしているユーザー(見せたくない)
     */
    public function setWhereNotInBlockUserIds($userId, $query,$column="user_id") {
        $blockList = self::getBlockAndBockedUsers($userId);
        if(empty($blockList))
            return $query;

        $query->whereNotIn($column,$blockList);
        return $query;
    }


    /**
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
				'users.description as introduction',
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
	 * @return mixed
	 */
	public function getBlockAndBlockedUserIds($userId){

		$blockUsers =
			$this
		    ->select('target_user_id')
		    ->where('user_id',$userId);
		$result =
			DB::table('blocked_users')
				->select('target_user_id')
				->where('user_id',$userId)
				->union($blockUsers)->get();

		if(empty($result)) return [];
		return array_values($result->toArray());

	}
}
