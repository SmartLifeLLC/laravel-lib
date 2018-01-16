<?php
/**
 * class ValidateRule
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/19
 */

namespace App\Constants;


use App\Lib\Enum;

final class AuthAllowedUrls
{
    const LIST = [
        ['url'=>"api/users/auth/",'method'=>HttpMethod::GET],
	    ['url'=>"user/auth",'method'=>HttpMethod::GET]

    ];
}