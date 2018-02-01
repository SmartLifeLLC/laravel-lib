<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:34
 */

namespace App\Http\Controllers\Translate;

use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousNotificationLogJsonView;
use App\Models\Old\NotificationLog;
use App\Services\Translate\PreviousNotificationLogService;
use DB;

class TranslateNotificationLogController extends Controller
{
    /**
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function translatePreviousData(){
        $results = array();

        $logs = (new NotificationLog())->getData();

        foreach ($logs as $log) {
            $logData = [
                'targetUserId' => $log->user_id,
                'message' => $log->message,
                'deliveredAt' => $log->created_at,
                'notificationLogTypeId' => $this->getTypeId($log->type)
            ];
            if($logData['notificationLogTypeId'] == 0) return $this->responseParameterErrorJsonViewWithDebugMessage("Failed to get type_id of {$log->id}");
            $detail = json_decode($log->detail_info, true);
            $logData += [
                'fromUserId' => $detail['queue_message'][0]['from_user_id'],
                'contributionId' => $detail['review_post_id'],
                'contributionCommentId' => $detail['review_post_comment_id'],
            ];

            $serviceResult = (new PreviousNotificationLogService())->getData($logData);

            $jsonView = (new PreviousNotificationLogJsonView($serviceResult));
            $results[] = $this->responseJson($jsonView);
        }
        return $results;
    }

    /**
     * @param $type
     * @return int
     */
    private function getTypeId($type){
        switch($type){
            case 'comment':
                return 1;
            case 'like':
                return 2;
            case 'have':
                return 3;
            case 'interest':
                return 4;
            case 'follow':
                return 5;
            case 'admin':
                return 6;
            default:
                return 0;
        }
    }
}