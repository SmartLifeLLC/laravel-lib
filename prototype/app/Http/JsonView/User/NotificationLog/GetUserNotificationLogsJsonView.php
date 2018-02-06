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
        $this->body = [
            'unread_count' => $this->data->getUnreadCount(),
            'logs' => $this->data->getNotificationLogData()
        ];
    }
}