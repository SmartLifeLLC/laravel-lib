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

class ReviewPostComments extends DBModel
{
    protected $guarded = [];


    public function getData()
    {
        $data = DB::connection('mysql_old')->ReviewPostComments->all();
        return $data;
    }
}