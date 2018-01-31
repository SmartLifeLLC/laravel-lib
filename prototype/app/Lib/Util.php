<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2017/12/04
 * Time: 7:15
 */

namespace App\Lib;


use App\Constants\SystemConstants;
use Illuminate\Http\Request;

final class Util
{
    static function getUniqueAuth():String{
        return uniqid();
    }

    /**
     * @param $imageUrl
     * @return String
     */
    static function getImageNameWithExtensionFromUrl($imageUrl):String{
        $pathInfo = pathinfo(explode("?",$imageUrl)[0]);
        return $pathInfo['basename'];
    }

	/**
	 * @param $userId
	 * @param $imageNameWithExtension
	 * @param $imageCategory
	 * @return String
	 */
    static function getS3KeyForImageName($userId, $imageNameWithExtension, $imageCategory):String{
        $s3key = $userId."/".$imageCategory."/".date('Y-m-d')."/".uniqid()."_".$imageNameWithExtension;
        return $s3key;
    }

    /**
     * @param $s3key
     * @return String
     */
    static function getS3SaveUrl($s3key):String{
        return "s3://".SystemConstants::S3ImageBucket()."/" .$s3key;
    }


	/**
	 * @param $s3key
	 * @return String
	 */
    static function getS3CdnUrl($s3key):String{
    	if(empty($s3key )) return "";
		return SystemConstants::getCdnHost()."/" .$s3key;
	}


    /**
     * @param $data
     * @param $key
     * @param $default
     * @return mixed
     */
    static function getValueForKey($data,$key,$default)
    {
        if (array_key_exists($key, $data)) {
            if (empty($data[$key]))
                return $default;
            else
                return $data[$key];
        } else {
            return $default;
        }

    }

    /**
     * @param Request $request
     * @param $key
     * @param $default
     * @return mixed
     */
    static function getValueForKeyOnGetRequest(Request $request,$key,$default){
        return (empty($request->get($key)))?$default:$request->get($key);
    }

    /**
     * @return mixed
     */
    static function getUniqueNumber(){
            return  str_replace('.','',sprintf('%f', microtime(true)));
    }

    /**
     * @param $length
     * @return string
     */
    static function getRandomString($length){
        $key = '';
        $keys = array_merge(range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }

    /**
     * @param $s3key
     * @return string
     */
    static function getS3ImageFullPath($s3key):String{
        return  ($s3key == null)?"":SystemConstants::getCdnHost().$s3key;
    }


    /**
     * from : https://qiita.com/a_yasui/items/fc6e1c564b5b21482882
     * @param $str
     * @return null|string|string[]
     */
    static function removeWhite ($str){
        return preg_replace(
            '/(?:'
            .'(?:\x09)'
            .'|(?:\x20)'
            .'|(?:\xc2\xa0)'
            .'|(?:\xe2\x80\x82)'
            .'|(?:\xe2\x80\x83)'
            .'|(?:\xe2\x80\x84)'
            .'|(?:\xe2\x80\x85)'
            .'|(?:\xe2\x80\x86)'
            .'|(?:\xe2\x80\x87)'
            .'|(?:\xe2\x80\x88)'
            .'|(?:\xe2\x80\x89)'
            .'|(?:\xe2\x80\x8a)'
            .'|(?:\xe2\x80\x8b)'
            .'|(?:\xe3\x80\x80)'
            .'|(?:\xef\xbb\xbf)'
            .')+/',
            '',
            $str);
    }

    /**
     *  全角英数字を半角に : 'a'
     *  全角スペースを半角に : 's'
     *  see http://php.net/manual/ja/function.mb-convert-kana.php
     * @param $value
     * @return string
     */
    static function convertToHankakuAlphaNum($value){
        return (mb_convert_kana(($value),"as"));
    }

    /**
     * @return string
     */
    static function getDateFormat(){
        return "Y-m-d";
    }

    /**
     * @param $str
     * @param $num
     * @param string $encoding
     * @return string
     */
    static function getNGram($str, $num, $encoding = 'UTF-8'){
        if($num == 0 ) return $str;
        if (func_num_args() < 3) {
            $encoding = mb_internal_encoding();
        }
        $strList = [];
        $strLen = mb_strlen($str, $encoding);

        for ($i = 0; $i < $strLen; ++$i) {
            $strList[] = mb_substr($str, $i, $num, $encoding);
        }
        return implode($strList, "\x20");
    }

    static function getStringWithMaxLength($string,$maxLength = 16){
	if(mb_strlen($string) > $maxLength){
		$string = mb_substr($string,0,16);
		$string .= "...";
	}
	return $string;
}
}