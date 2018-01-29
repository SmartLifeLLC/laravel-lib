<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/29
 * Time: 21:33
 */

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;
use App\Models\DBModel;

class ReviewPost extends DBModel
{
    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = DB::connection('mysql_old')
            ->table('review_posts')
            ->select('user_id', 'product_item_id', 'is_consent', 'image_ids', 'text', 'created_at')
            ->get();

        return $data;
    }
}