<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/23
 * Time: 23:06
 */

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;
use App\Models\DBModel;
use DB;

class ReviewPostComment extends DBModel
{
    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = DB::connection('mysql_old')
            ->table('review_post_comments')
            ->select('user_id', 'feed_id', 'text', 'created_at')
            ->get();

        return $data;
    }
}