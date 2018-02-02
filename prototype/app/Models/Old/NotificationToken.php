<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/31
 * Time: 22:13
 */

namespace App\Models\Old;

class NotificationToken extends OldModel
{
    protected $table = 'notification_tokens';

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = $this
            ->select('user_id',
                'device_token',
                'created_at',
                'updated_at'
            )
            ->get();

        return $data;
    }
}