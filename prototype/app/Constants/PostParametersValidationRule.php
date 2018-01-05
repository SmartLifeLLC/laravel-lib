<?php
/**
 * class ValidateRule
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/19
 */

namespace App\Constants;


class PostParametersValidationRule
{

    const TARGET_USER_ID = "to";
    const USER_ID = "user_id";
    const FACEBOOK_ID = "facebook_id";
    const NOTIFICATION_TOKEN = "notification_token";
    const DEVICE_UUID = "device_uuid";
    const DEVICE_TYPE = "device_type";
    const RULE_REQUIRED = 'required';
    const RULE_NUMERIC = 'numeric';
    const RULE_OR = '|';

    static $ALL_RULES=
    [
        PostParametersValidationRule::TARGET_USER_ID =>
             PostParametersValidationRule::RULE_REQUIRED
            .PostParametersValidationRule::RULE_OR
            .PostParametersValidationRule::RULE_NUMERIC,

        PostParametersValidationRule::USER_ID =>
             PostParametersValidationRule::RULE_REQUIRED
            .PostParametersValidationRule::RULE_OR
            .PostParametersValidationRule::RULE_NUMERIC,

        PostParametersValidationRule::FACEBOOK_ID =>
             PostParametersValidationRule::RULE_REQUIRED
            .PostParametersValidationRule::RULE_OR
            .PostParametersValidationRule::RULE_NUMERIC,

        PostParametersValidationRule::NOTIFICATION_TOKEN =>
            PostParametersValidationRule::RULE_REQUIRED,
        PostParametersValidationRule::DEVICE_UUID =>
            PostParametersValidationRule::RULE_REQUIRED,
        PostParametersValidationRule::DEVICE_TYPE=>
            PostParametersValidationRule::RULE_REQUIRED
    ];

    static function get(...$parameters){
        $rules = [];
        foreach ($parameters[0] as $parameter){
            if(array_key_exists($parameter,PostParametersValidationRule::$ALL_RULES)){
                $rules[$parameter] = PostParametersValidationRule::$ALL_RULES[$parameter];
            }
        }
        return $rules;
    }
}