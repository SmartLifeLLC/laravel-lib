<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/06
 * Time: 22:37
 */

namespace App\Http\JsonView\User\User;


use App\Http\JsonView\JsonResponseView;

class NotificationSettingJsonView extends JsonResponseView
{
    /**
     * @var see Models/User@getNotificationSettings
     */
    protected $data;
    public function createBody()
    {
        $user = $this->data;
        $this->body =
            [
                'is_follow_notification_allowed' => $user->is_follow_notification_allowed,
                'is_have_notification_allowed' => $user->is_have_notification_allowed,
                'is_like_notification_allowed' => $user->is_like_notification_allowed,
                'is_interest_notification_allowed'=>$user->is_interest_notification_allowed,
                'is_comment_notification_allowed' => $user->is_comment_notification_allowed
            ];
    }
}