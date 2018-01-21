<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 3:03
 */

namespace App\Constants;


class FeedFeelingType
{
	const NEGATIVE = "negative";
	const POSITIVE = "positive";

	static function getBinaryValue($stringValue){
		return ($stringValue == self::NEGATIVE)?0:1;

	}
}