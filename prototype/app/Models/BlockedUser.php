<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    protected $guarded = [];

    /**
     * @param $userId
     * @param $targetUserId
     * @return bool|null
     */
    public function deleteBlocked($userId,$targetUserId){
        return self::where(['user_id'=>$userId,'target_user_id'=>$targetUserId])->delete();
    }

    /**
     * @param $userId
     * @param $targetUserId
     * @return Model
     */

    public function createBlocked($userId,$targetUserId){
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
}
