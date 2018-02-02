<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/31
 * Time: 22:13
 */

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;
use App\Models\DBModel;
use DB;

class NotificationToken extends DBModel
{
    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = DB::connection('mysql_old')
            ->table('notification_tokens')
            ->select('user_id',
                'device_token',
                'registed_app_info',
                'created_at'
            )
            ->get();

        return $data;
    }
}