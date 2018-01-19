<?php
/**
 * class DateTimeFormat
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */

namespace App\Constants;


class DateTimeFormat
{
    const General = "Y-m-d H:i:s";
    const DateShort = "Y-m-d";

    static function getBirthdayFromFullDate($fullDate){
	    return explode(' ' , $fullDate)[0];
    }
}