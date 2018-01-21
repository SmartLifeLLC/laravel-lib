<?php
/**
 * class Gender
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/20
 */

namespace App\Constants;


final class Gender
{
    static function getString(int $codeId){
        switch ($codeId){
            case 1 : return "男性";
            case 2 : return "女性";
            case 3 : return "その他";
        }

    }
    
    static function getInt(String $fbGenderString){
        switch ($fbGenderString){
            case "male" : return 1;
            case "female" : return 2;
            default : return 3;
        }
    }

}