<?php

namespace App\Models;

use App\Constants\DateTimeFormat;
use App\ValueObject\SwitchFollowResultVO;
use Illuminate\Database\Eloquent\Model;
use DB;
class Follow extends DBModel
{

    protected $guarded = [];


	/**
	 * @param $userId
	 * @return int
	 */
	public function getCountForUser($userId):int{
		return self::where('user_id',$userId)->where('is_on',true)->count();
	}

	/**
	 * @param $userId
	 * @param $limit
	 * @param $page
	 * @return mixed
	 */
    public function getList($userId,$page,$limit){
	    $offset = $this->getOffset($limit,$page);
        $follows =
	        $this->select([
	        	's3_key',
		        'user_name',
		        'users.id as user_id',
		        'follows.id',
		        'users.description as introduction',
		        'followers.is_on as is_follower',
		        'follows.is_on as is_follow'])
	            ->where('follows.user_id',$userId)
	            ->leftJoin('users','users.id','=','follows.target_user_id')
		        ->leftJoin('images','users.profile_image_id','=','images.id')
		        ->leftJoin('followers',function($join) use ($userId){
			        $join->on('follows.target_user_id','=','followers.target_user_id');
			        $join->on('follows.user_id','=','follows.user_id');
			        $join->on('followers.is_on','=',DB::raw("1"));
		        })
	            ->limit($limit)
	            ->offset($offset)
	            ->get();
	    return $follows;
    }

    /**
     * @param $userId
     * @param $targetUserId
     * @return bool
     */
    public function isFollowStatus($userId, $targetUserId){
        $result =

                self::where(
                    [   'user_id'=>$userId,
                        'target_user_id'=>$targetUserId,
                        'is_on'=>true])
                ->first();

        if($result == null){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @param $userId
     * @return int
     */
    public function numFollows($userId){
        return self::where('user_id',$userId)->where('is_on',true)->count();
    }


    /**
     * @param $userId
     * @param $targetUserId
     * @param $onOrOff
     * @return SwitchFollowResultVO
     */
    public function switchFollow($userId, $targetUserId, $onOrOff):SwitchFollowResultVO{
        $result = self::updateOrCreate(
        //Conditions
            [
                'user_id'=>$userId,
                'target_user_id'=>$targetUserId],
            //Update value
            [
                'user_id'=>$userId,
                'target_user_id'=>$targetUserId,
                'is_on' => $onOrOff,
	            'updated_at' => date(DateTimeFormat::General)
	            ]
        );
        $makeFollowResultVO = new SwitchFollowResultVO($result->wasRecentlyCreated,$onOrOff);
        return $makeFollowResultVO;
    }
}
