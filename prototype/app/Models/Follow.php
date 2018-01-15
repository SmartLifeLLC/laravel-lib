<?php

namespace App\Models;

use App\ValueObject\SwitchFollowResultVO;
use Illuminate\Database\Eloquent\Model;

class Follow extends DBModel
{

    protected $guarded = [];

    /**
     * @param $userId
     * @return array
     */
    public function getFollowsArray($userId){
        $follows = self::where('user_id',$userId)->where('is_on',true)->get(['target_user_id'])->toArray();
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
                'is_on' => $onOrOff]
        );
        $makeFollowResultVO = new SwitchFollowResultVO($result->wasRecentlyCreated,$onOrOff);
        return $makeFollowResultVO;
    }
}
