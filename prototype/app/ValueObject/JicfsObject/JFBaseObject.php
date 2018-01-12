<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/11
 * Time: 5:58
 */

namespace App\ValueObject\JicfsObject;
use Mockery\Exception;

abstract class JFBaseObject
{

    //1 レコード区分 半角 	英数、カナ　1 「E」固定
    public $_1_recordId;

    //2 英数、カナ 半角 	2「A1」固定
    public $_2_dataType;

    //3 確定更新区分	半角  1：新規、2：再使用、3：変更、4：退避、5：退避戻し、9：削除
    public $_3_updateType;

    protected $varNames;
    public function __construct($data)
    {
        $this->varNames = array_keys(get_object_vars($this));
        $this->createVars($data);
    }

    abstract function createVars($data);
    abstract function getVarCount();
    public function getVarNameForNth($n){
        if($n == 0){
            throw new Exception("N must bigger than 0.");
        }

        foreach ($this->varNames as $name){
            if(strpos($name,"_".$n."_")!==false){
                return $name;
            }
        }

        throw new Exception("Failed to find var name for n {$n}");
    }

    public function getNthVar($nth){
        $name = $this-> getVarNameForNth($nth);
        return $this->{$name};
    }
}