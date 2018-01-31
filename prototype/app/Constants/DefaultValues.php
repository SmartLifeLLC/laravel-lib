<?php
/**
 * class ServiceStatus
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/19
 */

namespace App\Constants;


final class DefaultValues
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
        //jancodeによる商品は基本１か２つのはずだが念の為大きめの数字を設定
        const QUERY_DEFAULT_LIMIT_FOR_JANCODE = 100;
        const QUERY_DEFAULT_PAGE = 1;
        const PRODUCT_N_GRAM_SIZE = 2;
        const SWAGGER_TOKEN = "rt";
		const MAX_LENGTH_NOTIFICATION_USERNAME = 16;
		const MAX_LENGTH_NOTIFICATION_PRODUCT = 20;
}