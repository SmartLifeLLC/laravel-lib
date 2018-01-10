<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2017/12/04
 * Time: 7:15
 */

namespace App\Lib;


use App\Constants\ConfigConstants;

final class Util
{
    static function getUniqueAuth():String{
        return uniqid();
    }

    /**
     * @param $imageUrl
     * @return String
     */
    static function getImageNameFromUrl($imageUrl):String{
        $pathInfo = pathinfo(explode("?",$imageUrl)[0]);
        return $pathInfo['basename'];
    }

    /**
     * @param $imageName
     * @param $imageType
     * @return String
     */
    static function getS3KeyForImageName($imageName, $imageType):String{
        $s3key = date('Y-m-d')."/".$imageType."/".$imageName;
        return $s3key;
    }

    /**
     * @param $s3key
     * @return String
     */
    static function getS3SavePoint($s3key):String{
        return "s3://".ConfigConstants::S3ImageBucket()."/" .$s3key;
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
        return  ($s3key == null)?"":ConfigConstants::getCdnHost().$s3key;
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
}