<?php

namespace App\Models;

use App\ValueObject\SwitchFollowerResultVO;
use Illuminate\Database\Eloquent\Model;

class Follower extends DBModel
{

    protected $guarded = [];

    /**
     * @param $userId
     * @param $targetUserId
     * @return bool
     */
    public function isFollowerStatus($userId, $targetUserId){
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
     * @return array
     */
    public function getFollowersArray($userId){
        $followers = self::where('user_id',$userId)->where('is_on',true)->get(['target_user_id'])->toArray();
        return $followers;
    }

    /**
     * @param $userId
     * @return int
     */
    public function numFollowers($userId){
        return self::where('user_id',$userId)->where('is_on',true)->count();
    }

    /**
     * @param $userId
     * @param $targetUserId
     * @param $onOrOff
     * @return SwitchFollowerResultVO
     */
    public function switchFollower($userId, $targetUserId, $onOrOff){
        $result = self::updateOrCreate(
        //Conditions
            ['user_id'=>$userId,'target_user_id'=>$targetUserId],

            //Update value
            ['user_id'=>$userId,
                'target_user_id'=>$targetUserId,
                'is_on' => $onOrOff]
        );
        $switchFollowerResultVO = new SwitchFollowerResultVO($result->wasRecentlyCreated);
        return $switchFollowerResultVO;

    }

}
