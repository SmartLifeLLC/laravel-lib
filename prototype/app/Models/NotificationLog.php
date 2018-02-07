<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 14:34
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
use App\Constants\DefaultValues;
use App\Constants\QueryOrderTypes;
use Illuminate\Database\Eloquent\Model;
use DB;

class NotificationLog extends DBModel
{
    protected $guarded = [];
    public $timestamps = false;

    /**
     * @param $userId
     * @param int $boundaryId
     * @param int $limit
     * @param QueryOrderTypes|null $orderType
     * @return mixed
     */
    public function getLogs(int $userId, int $boundaryId, int $limit, ?QueryOrderTypes $orderType ) {
        $compareSymbol = $orderType->getQueryCompareSymbol();
        $queryBuilder =
	        $this
	            ->select(
	            	'my_follows.id as my_follow_id',
		            'product_id',
		            'notification_logs.id',
		            'from_user_id',
		            'message',
		            'read_at',
		            'extra_info',
		            'notification_log_type_id',
		            'images.s3_key as profile_image_s3_key',
		            'contribution_id',
		            'contribution_comment_id',
		            'delivered_at')
		        ->leftJoin('users','users.id','=','from_user_id')
		        ->leftJoin('images','images.id','=','users.profile_image_id')
		        ->leftJoin('follows as my_follows',function($join) use ($userId){
			        $join->on('from_user_id','=','my_follows.target_user_id');
			        $join->on('my_follows.user_id','=',DB::raw($userId));
			        $join->on('my_follows.is_on','=',DB::raw("1"));})
		        ->where('notification_logs.target_user_id',$userId)
	            ->orderBy('id', $orderType->getValue())
		        ->limit($limit);
        if($boundaryId > 0){
            $queryBuilder = $queryBuilder->where('notification_logs.id',$compareSymbol,$boundaryId);
        }
		return $queryBuilder->get();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUnreadCount($userId){
        $unreadCount = self::where('target_user_id',$userId)
            ->whereNull('read_at')
            ->count();
        return $unreadCount;
    }

    /**
     * @param array $ids
     * @return bool
     */
    public function updateReadDate(Array $ids){
        return self::whereIn('id', $ids)
            ->update(['read_at'=>date(DateTimeFormat::General)]);
    }

    /**
     * @param array $saveData
     */
    public function saveData(array $saveData){
        self::insert($saveData);
    }

    /**
     * @param $logData
     * @return mixed
     */
    public function translateGetId($logData)
    {
        return $this->insertGetId(
            [
                'id' => $logData['id'],
                'target_user_id' => $logData['targetUserId'],
                'from_user_id' => $logData['fromUserId'],
                'message' => $logData['message'],
                'delivered_at' => $logData['deliveredAt'],
                'contribution_id' => $logData['contributionId'],
                'contribution_comment_id' => $logData['contributionCommentId'],
                'notification_log_type_id' => $logData['notificationLogTypeId']
            ]
        );
    }
}