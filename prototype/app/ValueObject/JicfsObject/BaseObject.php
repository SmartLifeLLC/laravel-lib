<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/11
 * Time: 5:58
 */

namespace App\ValueObject\JicfsObject;
abstract class BaseObject
{
    protected $varNames;
    public function __construct($data)
    {
        $this->varNames = array_keys(get_object_vars($this));
        $this->createVars($data);
    }

    abstract function createVars($data);

    public function getVarNameFor($id){
        foreach ($this->varNames as $name){
            if(strpos($name,"_".$id."_")!==false){
                return $name;
            }
        }
    }
}