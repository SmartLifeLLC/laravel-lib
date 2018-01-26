<?php

namespace App\Models;

use App\Constants\DateTimeFormat;
use App\Models\Common\UserContentsCountBuilderImplements;
use App\Models\Common\UserContentsCountBuilderInterface;
use App\ValueObject\SwitchFollowResultVO;
use Illuminate\Database\Eloquent\Model;
use DB;
class Follow extends DBModel implements UserContentsCountBuilderInterface
{

	use UserContentsCountBuilderImplements;
    protected $guarded = [];


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
		        'follows.id',
		        'user_name',
		        'users.id as user_id',
		        'users.description',
		        'my_follows.is_on as is_follow'])
	            ->where('follows.user_id',$ownerId)
	            ->leftJoin('users','users.id','=','follows.target_user_id')
		        ->leftJoin('images','users.profile_image_id','=','images.id')
		        ->leftJoin('follows as my_follows',function($join) use ($userId){
			        $join->on('follows.target_user_id','=','my_follows.target_user_id');
			        $join->on('my_follows.user_id','=',DB::raw($userId));
			        $join->on('my_follows.is_on','=',DB::raw("1"));
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
    public function switchFollow($userId, $targetUserId, $onOrOff):SwitchFollowResultVO
    {
	    $result = self::updateOrCreate(
	    //Conditions
		    [
			    'user_id' => $userId,
			    'target_user_id' => $targetUserId],
		    //Update value
		    [
			    'user_id' => $userId,
			    'target_user_id' => $targetUserId,
			    'is_on' => $onOrOff,
			    'updated_at' => date(DateTimeFormat::General)
		    ]
	    );
	    $makeFollowResultVO = new SwitchFollowResultVO($result->wasRecentlyCreated, $onOrOff);
	    return $makeFollowResultVO;
    }

	/**
	 * @param $userId
	 * @return mixed
	 */
    public function getFollowUserIds($userId){
    	$result =  $this->where('user_id',$userId)->select('target_user_id')->get();
    	if(empty($result)) return [];
    	return array_values($this->getArrayWithoutKey($result->toArray(),'target_user_id'));
    }
}
