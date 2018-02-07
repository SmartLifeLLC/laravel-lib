<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 22:01
 */
namespace App\Http\JsonView\User\NotificationLog;
use App\Http\JsonView\JsonResponseView;
use App\ValueObject\NotificationLogListVO;
class GetUserNotificationLogsJsonView extends JsonResponseView
{
    /**
     * @var NotificationLogListVO
     */
    protected $data;
    function createBody()
    {

    	$notificationData = $this->data->getNotificationLogData();
    	$returnData = [];
    	foreach($notificationData as $d){

    		$data =
			    [
			    	'id' => $d['id'],
				    'from_user_id' => $d['from_user_id'],
				    'message' => $d['message'],
					'is_following' => $this->getBinaryValue($d['my_follow_id']),
				    'profile_image_url' => $this->getImageURLForS3Key($d['profile_image_s3_key']),
				    'read_at' => $d['read_at'],
				    'notification_log_type_id' => $d['notification_log_type_id'],
				    'product_id' =>(int) $d['product_id'],
				    'extra_info' => $d['extra_info'],
				    'contribution_comment_id' => $d['contribution_comment_id'],
				    'delivered_at' => $d['delivered_at']
			    ];
    		$returnData[] = $data;
	    }
	    $this->body = [
            'unread_count' => $this->data->getUnreadCount(),
            'logs' => $returnData
        ];
    }
}