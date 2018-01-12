<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/11
 * Time: 2:17
 */
// EA1 Data

namespace App\ValueObject\JicfsObject;
use App\Lib\Util;

class JANProductBaseInfoVO extends JFBaseObject
{
    use JANProductBaseInfoVars;

    function getVarCount()
    {
        return 55;
    }

    public function createVars($data){
        $arrayKey = 0;
        for($n = 1 ; $n < 56 ; $n ++){
            $iteration = 0;

            //機能分類
            //3 times for same field
            if($n >= 23 && $n <= 25) $iteration = 3;

            //販売限定項目
            //2 times for same field
            else if($n >= 26 && $n <= 28) $iteration = 2;

            //10 times for same field
            //38 = 検索キーワード
            else if($n == 38) $iteration = 10;
            $varName = $this->getVarNameForNth($n);

            for ($i = 0 ; $i < $iteration ; $i++) {

                //全角英数字を半角に - 14は商品名
                $value = $data[$arrayKey++];
                if($n == 14)  $value = Util::convertToHankakuAlphaNum($value)  ;
                $this->{$varName}[] = $value;
            }

            //繰り返しがない項目
            if($iteration == 0){
                $value = $data[$arrayKey++];
                $this->{$varName} = $value;
            }
        }
    }
}