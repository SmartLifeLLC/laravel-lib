<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/11
 * Time: 3:46
 */
namespace App\ValueObject\JicfsObject;

trait JANProductBaseInfoVars
{




    //4 共通商品コード（ＪＡＮコード）半角 英数、カナ	13	"キー項目（JAN、EAN、UPC）標準13桁／短縮8桁、UPCｺｰﾄﾞ（12桁）は先頭に0（ｾﾞﾛ）を加えて13桁とします"
    public $_4_janCode;

    //5 （ﾘｻﾞｰﾌﾞｴﾘｱ）	半角 英数、カナ	2	（共通商品ｺｰﾄﾞ世代）
    public $_5_reservedArea;

    //6 商品コード区分　半角	英数、カナ	1	"0：JAN/EAN標準、　1：JAN/EAN短縮、　2：UPC標準、　3：UPC短縮　※従来は標準・短縮識別ｺｰﾄﾞ"
    public $_6_productCodeType;

    //7	商品区分　半角	英数、カナ	2	00：一般、01：PB、02：SB、03：業務用、09：その他（EOS用、資材等）
    public $_7_productType;

    //8	輸入品フラグ	半角 英数、カナ	1	0：国産、1：輸入品（海外生産品を含む） ※従来の国産・輸入区分に相当
    public $_8_importType;

    //9	ＪＩＣＦＳ／ＩＦＤＢ検索用メーカコード 半角	英数、カナ	9	商品ﾒｰｶ単位の検索、集計ｷｰとして使用（代表ﾒｰｶｺｰﾄﾞ）
    public $_9_representativeManufacturerCode;

    //10	メーカ自社商品コード	半角	英数、カナ	20	商品ﾒｰｶの自社商品ｺｰﾄﾞまたは品番
    public $_10_manufacturerProductCode;

    //11 部品名称	企業ﾌﾞﾗﾝﾄﾞ名またはﾒｰｶ略称(カナ)		半角	英数、カナ	10	POPﾁﾗｼ名称や棚割ｿﾌﾄ等にも利用
    public $_11_brandManufacturerKana;

    //12 企業ﾌﾞﾗﾝﾄﾞ名またはﾒｰｶ略称(漢字)全角		漢字、英数、カナ	10
    public $_12_brandManufacturerKanji;

    //13 商品名称 （カナ）		半角	英数、カナ	50	商品の固有名称
    public $_13_productNameKana;

    //14 商品名称 (漢字) 全角
    public $_14_productNameKanji;

    //15 表示用規格 （カナ）半角	英数、カナ	10	商品の内容量、重量、規格等。　例）1500ml、200ｇ×5個入　等
    public $_15_displayUnitKana;

    //16 表示用規格 （漢字）	全角		漢字、英数、カナ	10
    public $_16_displayUnitKanji;

    //17 伝票用商品名称（カナ）		半角	英数、カナ	25	"伝票（統一伝票）等での表示用商品名称　 ﾌﾞﾗﾝﾄﾞ名または企業ﾌﾞﾗﾝﾄﾞ名またはﾒｰｶ略称＋商品名称＋表示用規格により自動編集可"
    public $_17_receiptProductNameKana;

    //18 	伝票用商品名称（漢字）	全角　	漢字、英数、カナ	25
    public $_18_receiptProductNameKanji;

    //19 POS	ＰＯＳレシート名（カナ）	半角	英数、カナ	14	POSやEOS（ﾊﾝﾃﾞｨﾀｰﾐﾅﾙ用）等で使用する商品略称名
    public $_19_posReceiptNameKana;

    //20 ＰＯＳレシート名（漢字）	全角		漢字、英数、カナ	14
    public $_20_posReceiptNameKanji;

    /***
     * 商品分類	ＪＩＣＦＳ商品分類（大分類）		○	英数、カナ	1
     * ＪＩＣＦＳ商品分類（中分類）		○	英数、カナ	1
     * ＪＩＣＦＳ商品分類（小分類）		○	英数、カナ	2
     * ＪＩＣＦＳ商品分類（細分類）		○	英数、カナ	2
     */
    public $_21_categoryCode;

    //22（ﾘｻﾞｰﾌﾞｴﾘｱ）			半角	英数、カナ	2
    public $_22_extraArea1;

    //23 機能分類	機能分類コード　　(１)～(３) 半角	英数、カナ	2	"JICFS分類以外に商品の機能や用途等の属性を識別、PRする場合に使用　例）保存方法、味覚区分、色、ｻｲｽﾞ、硬度、など　　　　"
    public $_23_functionCategoryCodes=[];

    //24  機能分類内容コード(１)～(３) 	半角 英数、カナ	3	機能分類の内容を表すコードを設定
    public $_24_functionDefailCode;

    //25 機能分類内容記述　(１)～(３) 	全角半角混在	漢字、英数、カナ	40	"ｺｰﾄﾞが不明または入力が面倒、あるいは内容を補足する場合に任意に記述（カナ、漢字混在）"
    public $_25_functionDetailDescription;

    //26 販売限定	限定区分コード　　(１)～(２) 半角	英数、カナ	2	"販売を限定する商品について限定の種類を指定　例）地域、シーズン、歳事、期間、数量、流通など"
    public $_26_limitEditionTypeCode;

    //27 限定区分内容コード(１)～(２)	半角  英数、カナ	3	限定内容をコードで指定　　例）関東、春夏、歳暮、2ヶ月間など
    public $_27_limitEditionDetailCode;

    //28 限定内容記述　(１)～(２)	混在 漢字、英数、カナ	40	"ｺｰﾄﾞが不明または入力が面倒、あるい内容を補足する場合に任意に記述（カナ、漢字混在）"
    public $_28_limitEditionDetailDescription;

    //29 価格 オープン価格フラグ	 半角 英数、カナ	1	0：通常、1：ｵｰﾌﾟﾝ価格
    public $_29_openPriceFlag;


    /**
     * price
     * @var
     */
    //30 希望小売価格	 半角　数字	8	内容については「消費税区分」で判断
    public $_30_price;

    //31 内容量	総内容量	半角	数字	7	"ﾕﾆｯﾄﾌﾟﾗｲｽ算出用、ﾏｰｹﾃｨﾝｸﾞ分析用。浮動小数点7桁：値の範囲 9999999（整数7桁）～0.00001（小数点第5位）　"
    public $_31_totalQuantity;

    //32 内容量単位コード	半角 英数、カナ	3	"総内容量の単位をｍｌ、ｇ、本、枚、個、粒などで指定（ｌ、Ｋｇは使用せず） ※項番31、32は従来の「商品規格」の各項目相当"
    public $_32_quantityUnityCode;

    //33 単品重量	半角 数字	7	単位はｇ
    public $_33_singleProductWeight;

    //34 容器形態コード（外装）	半角 英数、カナ	2	棚の陳列時の容器形態（原則として外装）。ﾏｰｹﾃｨﾝｸﾞ分析、棚割ｼｽﾃﾑに利用。
    public $_34_containerOutsideFormCode;

    //35 原産国	原産国コード		半角	英数、カナ	3	一括表示で示されるもの。複数ある場合には代表的なものを設定。
    public $_35_countryOfOrigin;

    //36　原産国記述	混在	漢字、英数、カナ	40	"原産国ｺｰﾄﾞが不明または入力が面倒な場合、および補足がある場合に任意に記述（カナ、漢字混在）"
    public $_36_countryOfOriginDetail;

    //37	商品コメント	混在 漢字、英数、カナ	400	原材料、成分表示、商品特徴などの他、ｷｬﾝﾍﾟｰﾝ情報や案内などを記述（カナ、漢字混在）
    public $_37_productComment;

    //38 検索キーワード  (１)～(１0)混在 漢字、英数、カナ	40	"ｷｰﾜｰﾄﾞ検索用。商品の機能、用途、PR情報、特徴などをｷｰﾜｰﾄﾞで記述（カナ、漢字混在）例）天然○○使用、本格派ﾌﾟﾛの味、健康志向、など"
    public $_38_searchKeywords=[];

    //39 有効	有効期間区分（賞味期間区分）半角	英数、カナ	1	1：日、2：月、3：年
    public $_39_expirationDateType;

    //40 有効期間（賞味期間）半角	数字	3	上記の年月日に続いて有効期間（賞味期間）を設定
    public $_40_expirationDatePeriod;

    //41 棚割	棚割サイズ（幅）半角 数字	4	棚割対象商品のみセット（単位＝mm）
    public $_41_tableSizeWidth;

    //42 棚割サイズ（高さ）半角 数字	4	棚割対象商品のみセット（単位＝mm）
    public $_42_tableSizeHeight;

    //43 	棚割サイズ（奥行き）		○	数字	4	棚割対象商品のみセット（単位＝mm）
    public $_43_tableSizeDepth;

    //44 発売開始日付			○	数字	8	YYYYMMDD（西暦年月日）
    public $_44_releaseDate;

    //45 製造・販売中止フラグ			○	英数、カナ	1	0：製造・販売中商品、　1：製造・販売中止商品
    public $_45_discontinuedFlag;

    //46 ITF情報フラグ			○	英数、カナ	1	0：ITF情報なし、　　1：ITF情報あり
    public $_46_ITFFlag ;

    //47 内訳情報フラグ			○	英数、カナ	1	0：内訳情報なし、　 1：内訳情報あり
    public $_47_DetailFlag;

    //48（ﾘｻﾞｰﾌﾞｴﾘｱ）			○	英数、カナ	1	（メーカ直登録フラグ）
    public $_48_reservedArea;

    //49 初回登録日付			○	数字	8	YYYYMMDD（西暦年月日）
    public $_49_firstRegistrationDate;

    //50 最新更新日付			○	数字	8	YYYYMMDD（西暦年月日）
    public $_50_latestRegistrationDate;

    //51 伝票用商品名称(漢字)自動挿入ﾌﾗｸﾞ			○	英数、カナ	1	1：伝票用商品名称（カナ）から自動挿入あり
    public $_51_receiptNameKanjiAutoInputFlag;

    //52 POSレシート名(カナ)自動挿入ﾌﾗｸﾞ			○	英数、カナ	1	1：JICFS商品分類名称（カナ）から自動挿入あり
    public $_52_posReceiptNameKanaAutoInputFlag;

    //53	POSレシート名(漢字)自動挿入ﾌﾗｸﾞ			○	英数、カナ	1	1：JICFS商品分類名称（漢字）から自動挿入あり
    public $_53_posReceiptNameKanjiAutoInputFlag;

    //54	消費税区分			○	英数、カナ	1	1：総額　2：不課税　3：非課税　8：移行データ　9：本体　0：設定なし
    public $_54_taxType;

    //55 消費税率			○	数字	3	％表示、0～999％
    public $_55_taxRate;

}