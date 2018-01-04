<?php
/**
 * class ValidateRule
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/19
 */

namespace App\Constants;


class ValidateRule
{

    const TARGET_USER_ID = "to";
    const USER_ID = "user_id";
    const FACEBOOK_ID = "facebook_id";
    const RULE_REQUIRED = 'required';
    const RULE_NUMERIC = 'numeric';
    const RULE_OR = '|';

    static $ALL_RULES=
    [
        ValidateRule::TARGET_USER_ID =>
             ValidateRule::RULE_REQUIRED
            .ValidateRule::RULE_OR
            .ValidateRule::RULE_NUMERIC,

        ValidateRule::USER_ID =>
             ValidateRule::RULE_REQUIRED
            .ValidateRule::RULE_OR
            .ValidateRule::RULE_NUMERIC,

          ValidateRule::FACEBOOK_ID =>
             ValidateRule::RULE_REQUIRED
            .ValidateRule::RULE_OR
            .ValidateRule::RULE_NUMERIC
    ];

    static function get(...$parameters){
        $rules = [];
        foreach ($parameters[0] as $parameter){
            if(array_key_exists($parameter,ValidateRule::$ALL_RULES)){
                $rules[$parameter] = ValidateRule::$ALL_RULES[$parameter];
            }
        }
        return $rules;
    }
}