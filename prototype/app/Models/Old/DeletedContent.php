<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/31
 * Time: 21:46
 */

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;
use App\Models\DBModel;
use DB;

class DeletedContent extends DBModel
{
    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = DB::connection('mysql_old')
            ->table('deleted_contents')
            ->select('target_id', 'target_table', 'user_id', 'contents_detail', 'related_data', 'created_at')
            ->get();

        return $data;
    }
}