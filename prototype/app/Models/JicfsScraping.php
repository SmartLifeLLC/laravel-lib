<?php
/**
 * class JicfsScraping
 * @package App\Models
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/13
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class JicfsScraping extends DBModel
{
    public $timestamps = false;


    /**
     * @param  int $jicfsProductId
     * @return $this|Model|int
     */
    public function createGetId($jicfsProductId){
        $data = ['jicfs_product_id'=>$jicfsProductId];
        return $this->insertGetId($data);
    }
}