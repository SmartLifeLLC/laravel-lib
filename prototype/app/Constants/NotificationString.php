<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/30
 * Time: 10:43
 */

namespace App\Constants;


final class NotificationString
{
	static function getComment($userName,$productName){
		return
			"あなたの投稿に{$userName}さんがコメントしました\r\n{$productName}";
	}

	static function getCommentRelated($userName,$productName){
		return
			"あなたがコメントした投稿に対して{$userName}さんが新しくコメントしました\r\n{$productName}";
	}

	static function getFollow($userName){
		return
			"{$userName}さんがあなたをフォローしました";
	}

	/**
	 * @param $userName
	 * @param $productName
	 * @param $reactionType
	 * @return string
	 */
	static function getReaction($userName,$productName,$reactionType){
		$reactionString ="";
		switch ($reactionType){
			case ContributionReactionType::LIKE:
				$reactionString = "いいね";break;
			case ContributionReactionType::INTEREST:
				$reactionString = "気になる";break;
			default:
				break;
		}
		return
			"あなたの投稿に{$userName}さんが{$reactionString}しました\r\n{$productName}";
	}
}