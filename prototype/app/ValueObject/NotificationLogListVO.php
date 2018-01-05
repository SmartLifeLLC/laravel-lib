<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 21:38
 */

namespace App\ValueObject;


class NotificationLogListVO
{
    private $unreadCount = 0;
    private $notificationLogData = [];

    /**
     * @return int
     */
    public function getUnreadCount(): int
    {
        return $this->unreadCount;
    }

    /**
     * @return array
     */
    public function getNotificationLogData(): array
    {
        return $this->notificationLogData;
    }


    /**
     * NotificationLogsVO constructor.
     * @param $unreadCount
     * @param $logData
     */
    public function __construct($unreadCount,$logData)
    {
        $this->unreadCount = $unreadCount;
        $this->notificationLogData = $logData;
    }
}