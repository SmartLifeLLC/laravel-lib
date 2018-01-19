<?php
/**
 * class ValidateRule
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/19
 */

namespace App\Constants;


use App\Models\Product;

class PostParametersValidationRule
{

	const FEED_COMMENT_ID = "review_post_comment_id";
    const TARGET_USER_ID = "to";
    const USER_ID = "user_id";
    const FACEBOOK_ID = "facebook_id";
    const NOTIFICATION_TOKEN = "notification_token";
    const DEVICE_UUID = "device_uuid";
    const DEVICE_TYPE = "device_type";
    const PRODUCT_ID = "product_item_id";
	const REACTION_TYPE = "review_post_reaction_type";
    const PRODUCT_FEED_TYPE = "is_consent";
    const FEED_ID = "review_post_id";
    const FEED_CONTENT = "text";
    const COMMENT_CONTENT = "text";
    const IMAGE1 = "image1";
    const IMAGE2 = "image2";
    const IMAGE3 = "image3";
    const IMAGE4 = "image4";
    const RULE_REQUIRED = 'required';
    const RULE_NUMERIC = 'numeric';
    const RULE_OR = '|';
    const RULE_IMAGE = "image|mimes:jpeg,png,jpg,gif|max:2048";



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
            PostParametersValidationRule::RULE_REQUIRED,

        PostParametersValidationRule::IMAGE1=>
            PostParametersValidationRule::RULE_REQUIRED.
            PostParametersValidationRule::RULE_OR.
            PostParametersValidationRule::RULE_IMAGE,

        PostParametersValidationRule::IMAGE2=>
            PostParametersValidationRule::RULE_IMAGE,

        PostParametersValidationRule::IMAGE3=>
            PostParametersValidationRule::RULE_IMAGE,

        PostParametersValidationRule::IMAGE4=>
            PostParametersValidationRule::RULE_IMAGE,

        PostParametersValidationRule::PRODUCT_ID=>
           PostParametersValidationRule::RULE_REQUIRED
            .PostParametersValidationRule::RULE_OR
            .PostParametersValidationRule::RULE_NUMERIC,


        PostParametersValidationRule::PRODUCT_FEED_TYPE=>
            PostParametersValidationRule::RULE_REQUIRED
            .PostParametersValidationRule::RULE_OR
            .PostParametersValidationRule::RULE_NUMERIC,

	    PostParametersValidationRule::FEED_ID =>
	        PostParametersValidationRule::RULE_REQUIRED
	        .PostParametersValidationRule::RULE_OR
	        .PostParametersValidationRule::RULE_NUMERIC,

	    PostParametersValidationRule::COMMENT_CONTENT =>
	        PostParametersValidationRule::RULE_REQUIRED,

	    PostParametersValidationRule::FEED_CONTENT =>
		    PostParametersValidationRule::RULE_REQUIRED,

	    PostParametersValidationRule::FEED_COMMENT_ID =>
	        PostParametersValidationRule::RULE_REQUIRED
	        .PostParametersValidationRule::RULE_OR
	        .PostParametersValidationRule::RULE_NUMERIC,

	    PostParametersValidationRule::REACTION_TYPE =>
		    PostParametersValidationRule::RULE_REQUIRED
		    .PostParametersValidationRule::RULE_OR
		    .PostParametersValidationRule::RULE_NUMERIC,

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