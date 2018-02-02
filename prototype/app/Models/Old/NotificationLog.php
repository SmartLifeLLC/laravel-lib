<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/30
 * Time: 17:42
 */

namespace App\Models\Old;

class NotificationLog extends OldModel
{
    protected $table = 'notification_log';
    /**
     * @return mixed
     */
    public function getData()
    {
        $data = $this
            ->select('user_id',
                'detail_info',
                'message',
                'created_at',
                'type'
            )
            ->get();

        return $data;
    }
}