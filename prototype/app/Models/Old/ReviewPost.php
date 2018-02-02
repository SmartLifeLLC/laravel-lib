<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/29
 * Time: 21:33
 */

namespace App\Models\Old;

class ReviewPost extends OldModel
{
    protected $table = 'review_post';
    /**
     * @return mixed
     */
    public function getData()
    {
        $data = $this
            ->select('user_id', 'product_item_id', 'is_consent', 'image_ids', 'text', 'created_at')
            ->get();

        return $data;
    }
}