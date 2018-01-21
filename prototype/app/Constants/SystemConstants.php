<?php
/**
 * class ConfigConstants
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/21
 */

namespace App\Constants;


final class SystemConstants
{
    const ENV_PRODUCTION = "prd";
    const ENV_DEVELOPMENT = "dev";
    const ENV_STAGING = "stg";
    const ENV_LOCAL = "local";


    static function getCdnHost(){
        if(config("app.env") == SystemConstants::ENV_DEVELOPMENT){
            return URLs::IMG_HOST_DEVP;
        }else{
            return URLs::IMG_HOST;
        }
    }


    static function S3ImageBucket(){
        $env = config("app.env");
        switch ($env){
            case SystemConstants::ENV_LOCAL:
                return S3Buckets::IMG_LOCAL;
            case SystemConstants::ENV_DEVELOPMENT:
                return S3Buckets::IMG_DEV;
            case SystemConstants::ENV_STAGING:
                return S3Buckets::IMG_STG;
            case SystemConstants::ENV_PRODUCTION:
                return S3Buckets::IMG_PRD;
        }
    }
}