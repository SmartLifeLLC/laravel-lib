<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/06
 * Time: 22:37
 */

namespace App\Http\JsonView\User\User;
use App\Http\JsonView\JsonResponseView;

class NotificationSettingEditJsonView extends JsonResponseView
{
    /**
     * @var see Models/User@getNotificationSettings
     */
    protected $data;
    public function createBody()
    {
        $this->body =
            [
            	'message' => "変更完了しました。"
            ];
    }
}