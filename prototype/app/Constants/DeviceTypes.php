<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 13:35
 */

namespace App\Constants;


use App\Lib\Enum;

class DeviceTypes extends Enum
{
    const iPhone = "iPhone";
    const iPad = "iPad";
    const Android = "Android";
    const Web = "Web";
    const Kindle = "Kindle";
    const Etc = "Etc";
}