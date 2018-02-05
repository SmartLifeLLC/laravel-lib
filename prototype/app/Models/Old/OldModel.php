<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/02/03
 * Time: 0:50
 */

namespace App\Models\Old;


use App\Models\DBModel;

class OldModel extends DBModel
{
    protected $connection = 'mysql_old';
    protected $guarded = [];
}