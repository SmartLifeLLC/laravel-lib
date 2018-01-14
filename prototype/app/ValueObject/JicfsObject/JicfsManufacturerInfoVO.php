<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/11
 * Time: 2:18
 */
namespace App\ValueObject\JicfsObject;
use App\Lib\Util;

class JicfsManufacturerInfoVO extends JFBaseObject{


    //4	標準/短縮識別区分		半角　英数、カナ	1	「１」固定
    public $_4_standardOrShortType;

    //5	標準メーカコード		半角　英数、カナ	9	標準ﾒｰｶｺｰﾄﾞ
    public $_5_standardManufacturerCode;

    /**
     * 検索用のID
     * @var
     */
    //6 代表メーカコード		半角	英数、カナ	9	代表ﾒｰｶｺｰﾄﾞ =>
    public  $_6_representativeManufacturerCode;

    /**
     * Brand name
     * @var
     */
    //7 会社名	全角 漢字、英数、カナ	30	法人形態を含まず
    public  $_7_companyName;

    //8 会社名カナ　半角	英数、カナ	40	法人形態を含まず
    public  $_8_companyNameKana;

    //9 法人形態（位置）半角英数、カナ	1	0：称号無、1：前、2：後
    public  $_9_companyTypePosition;

    //10 10	法人形態（文字）半角　英数、カナ	2	"00：表示無し、01：株式会社、02：有限会社、03：合資会社、04：合名会社、10	法人形態（文字）		○	英数、カナ	2	"00：表示無し、01：株式会社、02：有限会社、03：合資会社、04：合名会社、
    public  $_10_companyType;

    //11 住所１	全角		漢字、英数、カナ	10	都道府県、市郡、東京23区
    public  $_11_address;

    //12 登録年月日 半角	数字	8
    public  $_12_registerDate;

    //13 内容変更年月日	半角　数字	8	YYYYMMDD
    public  $_13_modifiedDate;

    //14 廃止年月日	半角	数字	8	YYYYMMDD
    public  $_14_terminatedDate;


    /**
     * @param $data
     */
    function createVars($data)
    {
        $arrayKey = 0;
        for($n = 1 ; $n < 15 ; $n ++){
            $value = $data[$arrayKey++];
            $varName = $this->getVarNameForNth($n);
            //Change from zenkaku  to hankaku for alphabet and numeric data.
            if($n == 7)  $value =  Util::convertToHankakuAlphaNum($value)  ;
            $this->{$varName} = $value;
        }
    }

    function getVarCount()
    {
        return 14;
    }

}