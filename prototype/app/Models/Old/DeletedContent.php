<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/31
 * Time: 21:46
 */

namespace App\Models\Old;

class DeletedContent extends OldModel
{
    protected $table= 'deleted_contents';
    /**
     * @return mixed
     */
    public function getData()
    {
        $data = $this
            ->select('id', 'target_id', 'target_table', 'user_id', 'contents_detail', 'related_data', 'created_at')
            ->get();

        return $data;
    }
}