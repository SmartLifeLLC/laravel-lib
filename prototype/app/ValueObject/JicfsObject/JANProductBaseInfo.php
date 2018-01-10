<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/11
 * Time: 2:17
 */
// EA1 Data

namespace App\ValueObject\JicfsObject;
class JANProductBaseInfo extends BaseObject
{
    use JANProductBaseInfoVars;

    public function createVars($data){
        $arrayKey = 0;
        for($methodId = 1 ; $methodId < 56 ; $methodId ++){
            $iteration = 0;
            //3 times for same field
            if($methodId >= 23 && $methodId <= 25) $iteration = 3;

            //2 times for same field
            else if($methodId >= 26 && $methodId <= 28) $iteration = 2;

            //10 times for same field
            else if($methodId == 38) $iteration = 10;
            $varName = $this->getVarNameFor($methodId);

            for ($i = 0 ; $i < $iteration ; $i++) {
                $value = $data[$arrayKey++];
                $this->{$varName}[] = $value;
            }

            if($iteration == 0){
                $value = $data[$arrayKey++];
                $this->{$varName} = $value;
            }
        }
    }
}