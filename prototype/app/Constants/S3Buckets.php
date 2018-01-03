<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2017/12/03
 * Time: 21:48
 */

namespace App\Constants;


use App\Lib\Enum;

class S3Buckets extends Enum
{
    const IMG_LOCAL = "local-react-contents";
    const IMG_DEV = "dev-react-contents";
    const IMG_STG = "stg-react-contents";
    const IMG_PRD = "prd-react-contents";
    const REGION = "ap-northeast-1";
}