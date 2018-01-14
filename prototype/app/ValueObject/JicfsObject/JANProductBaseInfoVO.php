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
        //資料のi回繰り返しに対応
        $iterationInfosForNthValue =
            [
                23 => 3,  //機能分類
                24 => 3,
                25 => 3,
                26 => 2, //販売限定項目
                27 => 2,
                28 => 2,
                38 => 10 //38 = 検索キーワード
            ];

       //全角英数字、スペースを半角にする項目
        $hankakuTranslates =
            [
                14,    //14 商品名称 (漢字) 全角
                16,    //16 表示用規格 （漢字）	全角		漢字、英数、カナ	10
                18,    //18 伝票用商品名称（漢字）	全角　	漢字、英数、カナ	25
                20     //20 ＰＯＳレシート名（漢字）	全角		漢字、英数、カナ 14
            ];

        for($n = 1 ; $n < 56 ; $n ++){
            $varName = $this->getVarNameForNth($n);
            if(array_key_exists($n,$iterationInfosForNthValue)){
                $iteration = $iterationInfosForNthValue[$n];
                for ($i = 0 ; $i < $iteration ; $i++) {
                    $value = $data[$arrayKey++];
                    $this->{$varName}[] = $value;
                }
            }else{
                $value = $data[$arrayKey++];
                //全角英数字を半角に - 14は商品名 20 - pos レジ名　16 - 表示単位
                if(in_array($n,$hankakuTranslates)) {
                    $value = Util::convertToHankakuAlphaNum($value)  ;
                }
                $this->{$varName} = $value;
            }
        }
    }
}