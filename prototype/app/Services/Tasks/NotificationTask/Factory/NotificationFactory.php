<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/29
 * Time: 16:14
 */

namespace App\Services\Tasks\NotificationTask\Factory;
use App\Services\Tasks\NotificationTask\Notification;
interface NotificationFactory
{
	public function create(array $targetUserIds, int $fromUserId, $contentId, $product, ):Notification;
}