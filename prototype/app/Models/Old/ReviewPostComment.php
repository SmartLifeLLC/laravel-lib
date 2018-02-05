<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/23
 * Time: 23:06
 */

namespace App\Models\Old;

class ReviewPostComment extends OldModel
{
    protected $table = 'review_post_comments';

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = $this
            ->select('user_id', 'review_post_id', 'text', 'created_at')
            ->get();

        return $data;
    }
}