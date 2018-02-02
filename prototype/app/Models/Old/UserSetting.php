<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/29
 * Time: 23:20
 */

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;
use App\Models\DBModel;
use DB;

class UserSetting extends DBModel
{
    protected $guarded = [];
    protected $connection = 'mysql_old';
    protected $table = 'user_settings';

    /**
     * @return mixed
     */
    public function getData($userId)
    {
        $data = $this
            ->where('user_id', $userId)
            ->select('can_notice_follow',
                'can_notice_having',
                'can_notice_like',
                'can_notice_interest',
                'can_notice_comment'
            )
            ->first();

        return $data;
    }
}