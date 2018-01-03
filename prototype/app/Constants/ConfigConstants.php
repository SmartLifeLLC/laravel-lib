<?php
/**
 * class ConfigConstants
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/21
 */

namespace App\Constants;


final class ConfigConstants
{
    const ENV_PRODUCTION = "prd";
    const ENV_DEVELOPMENT = "dev";
    const ENV_STAGING = "stg";
    const ENV_LOCAL = "local";

    static function getCdnHost(){

        if(config("app.env") == ConfigConstants::ENV_DEVELOPMENT){
            return URLs::IMG_HOST_DEVP;
        }else{
            return URLs::IMG_HOST;
        }
    }


    static function S3ImageBucket(){
        $env = config("app.env");
        switch ($env){
            case ConfigConstants::ENV_LOCAL:
                return S3Buckets::IMG_LOCAL;
            case ConfigConstants::ENV_DEVELOPMENT:
                return S3Buckets::IMG_DEV;
            case ConfigConstants::ENV_STAGING:
                return S3Buckets::IMG_STG;
            case ConfigConstants::ENV_PRODUCTION:
                return S3Buckets::IMG_PRD;
        }
    }
}