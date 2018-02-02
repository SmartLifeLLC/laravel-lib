<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/30
 * Time: 17:42
 */

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;
use App\Models\DBModel;
use DB;

class NotificationLog extends DBModel
{
    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = DB::connection('mysql_old')
            ->table('notification_log')
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