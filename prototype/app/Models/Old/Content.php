<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/30
 * Time: 16:11
 */

namespace App\Models\Old;

class Content extends OldModel
{
    protected $table = 'contents';
    /**
     * @return mixed
     */
    public function getData()
    {
        $data = $this
            ->select('id', 'user_id', 's3_key', 'type', 'created_at')
            ->get();

        return $data;
    }
}