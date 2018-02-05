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
class GetAdminNotificationLogsJsonView extends JsonResponseView
{
    /**
     * @var array
     */
    protected $data;
    function createBody()
    {
        $this->body = [ 'logs' => $this->data ];
    }
}