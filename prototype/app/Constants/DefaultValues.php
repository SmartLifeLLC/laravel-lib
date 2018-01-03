<?php
/**
 * class ServiceStatus
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/19
 */

namespace App\Constants;


use App\Lib\Enum;

final class DefaultValues extends Enum
{
        const SETTING_USER_AGE_PERMISSION = 1;
        const SETTING_USER_GENDER_PERMISSION = 1;
        const CONTENTS_USER_PROFILE_IMG_ID = 0;
        const CONTENTS_USER_COVER_IMG_ID = 0;
        const USER_FIRST_NAME_MAX = 10000;
        const USER_LAST_NAME_MAX =  10000;
        const USER_NAME_MAX = 10000;
        const USER_DESCRIPTION_MAX = 255;
        const QUERY_DEFAULT_LIMIT = 10;

}