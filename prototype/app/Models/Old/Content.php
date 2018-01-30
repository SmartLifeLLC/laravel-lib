<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/30
 * Time: 16:11
 */

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;
use App\Models\DBModel;

class Content extends DBModel
{
    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = DB::connection('mysql_old')
            ->table('contents')
            ->select('user_id', 's3_key', 'type', 'created_at')
            ->get();

        return $data;
    }
}