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
        $model = self::where('user_id',$userId);
        if($boundaryId > 0){
            $model = $model->where('id',$compareSymbol,$boundaryId);
        }

        $logs =  $model->orderBy('id', $orderType->getValue())->limit($limit)->get();
        return $logs;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUnreadCount($userId){
        $unreadCount = self::where('user_id',$userId)
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
     * @param $logData
     * @return mixed
     */
    public function translateGetId($logData){
        return $this->insertGetId(
            [
                'target_user_id'=>$logData['targetUserId'],
                'from_user_id'=>$logData['fromUserId'],
                'message'=>$logData['message'],
                'delivered_at'=>$logData['deliveredAt'],
                'contribution_id'=>$logData['contributionId'],
                'contribution_comment_id'=>$logData['contributionCommentId'],
                'notification_log_type_id'=>$logData['notificationLogTypeId']
            ]
        );
    }
}