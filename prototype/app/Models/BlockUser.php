<?php

namespace App\Models;

use App\Constants\ConfigConstants;
use Illuminate\Database\Eloquent\Model;
use DB;
class BlockUser extends Model
{
    protected $guarded = [];


    /**
     * @param $userId
     * @param $targetUserId
     * @return bool|null
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
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getBlockUsers($userId,$offset=0,$limit=10){
        $imgHost = ConfigConstants::getCdnHost();

        $query =
        "
        SELECT
          users.id,
          users.description AS introduction,
          concat(?, s3_key) AS profile_image_url,
          user_name
        FROM block_users
          LEFT JOIN users ON block_users.target_user_id = users.id
          LEFT JOIN contents ON users.profile_image_id = contents.id
        WHERE block_users.user_id = ?
        LIMIT ?, ?;        
        ";

        $params = [$imgHost,$userId,$offset,$limit];
        $blockUsers = DB::select($query,$params);
        return $blockUsers;
    }


}
