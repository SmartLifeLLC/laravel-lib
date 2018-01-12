<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/12
 * Time: 2:22
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
class ProductBrand extends Model
{
    /**
     * @param $id
     * @param $name
     * @return mixed
     */
    public function create($id,$name){
        $data = ['id'=>$id,'name'=>$name];
        return $this->insertGetId($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getBrand($id){
        return $this->find($id);
    }
}