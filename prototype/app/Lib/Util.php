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
}