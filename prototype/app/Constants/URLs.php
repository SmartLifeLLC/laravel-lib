<?php
/**
 * class URLs
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/20
 */

namespace App\Constants;


final class URLs
{

    const IMG_HOST = 'https://cdn.recomil.com/';
    const IMG_HOST_DEVP = "https://dev-cdn.recomil.com/";
    const API_FB_USER_INFO =
        "https://graph.facebook.com/me?fields=id,email,gender,link,locale,name,timezone,updated_time,verified,last_name,first_name,middle_name,birthday,about,picture,cover,location&access_token=";
    const FIREBASE_FCM_ENDPOINT = "https://fcm.googleapis.com/fcm/send";
    const API_FB_USER_FRIEND_FORMAT = "https://graph.facebook.com/%s/friends?access_token=%s&limit=500&%s";
}