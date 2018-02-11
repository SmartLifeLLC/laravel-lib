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
    		$user = $this->getUserHashArray($d['from_user_id'],$d['user_name'],$d['profile_image_s3_key'],$d['my_follow_id'],$d['description']);

    		$data =
			    [
			    	'id' => $d['id'],
				    'from_user'=>$user,
				    'message' => $d['message'],
				    'read_at' => $d['read_at'],
				    'contribution_id' => $d['contribution_id'],
				    'contribution_comment_id' => $d['contribution_comment_id'],
				    'contribution_comment_content' => $this->getNotNullString($d['contribution_comment_content']),
				    'notification_log_type_id' => $d['notification_log_type_id'],
				    'product_id' =>(int) $d['product_id'],
				    'product_name'=> $this->getNotNullString($d['display_name']),
				    'delivered_at' => $d['delivered_at'],
				    'extra_info' => $d['extra_info'],
			    ];
    		$returnData[] = $data;
	    }
	    $this->body = [
            'unread_count' => $this->data->getUnreadCount(),
            'logs' => $returnData
        ];
    }
}