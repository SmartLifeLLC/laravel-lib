<?php

namespace App\Models;

use App\Constants\DateTimeFormat;
use App\Models\Common\UserContentsCountBuilderImplements;
use App\Models\Common\UserContentsCountBuilderInterface;
use App\ValueObject\SwitchFollowerResultVO;
use Illuminate\Database\Eloquent\Model;
use DB;
class Follower extends DBModel implements UserContentsCountBuilderInterface
{
	use UserContentsCountBuilderImplements;
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
	 * @param $ownerId
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getList($userId,$ownerId,$page,$limit){
		$offset = $this->getOffset($limit,$page);
		$follows =
			$this->select([
				's3_key',
				'user_name',
				'users.id as user_id',
				'users.description',
				'follows.is_on as is_follow'])
				->where('followers.user_id',$ownerId)
				->leftJoin('users','users.id','=','followers.target_user_id')
				->leftJoin('images','users.profile_image_id','=','images.id')
				->leftJoin('follows',function($join) use ($userId){
					$join->on('follows.target_user_id','=','followers.target_user_id');
					$join->on('follows.user_id','=',DB::raw($userId));
					$join->on('follows.is_on','=',DB::raw("1"));
				})
				->limit($limit)
				->offset($offset)
				->get();
		return $follows;
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
	 * @param array $blockList
	 * @return int
	 */
	public function getCountForUser($userId,$blockList = []):int{
		return $this->getCountQueryForUser($userId,$blockList)->where('is_on',true)->count();
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
                'is_on' => $onOrOff,
	            'updated_at' => date(DateTimeFormat::General)
	            ]
        );
        $switchFollowerResultVO = new SwitchFollowerResultVO($result->wasRecentlyCreated);
        return $switchFollowerResultVO;

    }

}
