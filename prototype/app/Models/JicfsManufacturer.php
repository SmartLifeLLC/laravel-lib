<?php
/**
 * class JicfsManufacturer
 * @package App\Models
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/13
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class JicfsManufacturer extends DBModel
{
    /**
     * @param $id
     * @param $name
     * @return mixed
     */
    public function createGetId($id,$name){
        $data = ['id'=>$id,'name'=>$name];
        return $this->insertGetId($data);
    }

}