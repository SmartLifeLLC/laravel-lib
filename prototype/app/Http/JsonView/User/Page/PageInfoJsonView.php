<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/22
 * Time: 2:06
 */

namespace App\Http\JsonView\User\Page;


use App\Http\JsonView\JsonResponseView;
use App\ValueObject\PageInfoResultVO;

class PageInfoJsonView extends JsonResponseView
{

	/**
	 * @var PageInfoResultVO
	 */
	protected $data;
	function createBody()
	{
		$user = $this->data->getUserInfoForPage();
		$contributionCount = $this->data->getContributionCount();
		$allReactionCounts = $this->data->getAllReactionCounts();

		$this->body =
			[
				'user_id' => $user['id'],
				'user_name' => $user['user_name'],
				'profile_image_url' => $this->getImageURLForS3Key($user['profile_image_s3_key']),
				'cover_image_url' => $this->getImageURLForS3Key($user['cover_image_s3_key']),
				'introduction' => $user['description'],
				'birthday' => $this->getBirthdayString($user['birthday'],$user['birthday_published_flag']),
				'gender' => $this->getGenderString($user['gender'],$user['gender_published_flag']),
				'gender' => $this->getGenderString($user['gender'],$user['gender_published_flag']),
				'review' => $contributionCount,
		//		'like' => $allReactionCounts['like'],
				'interest' => $allReactionCounts['interest'],
		//		'having' => $allReactionCounts['have'],
				'follow' => (int) $user['follow_count'],
				'follower' => (int) $user['follower_count'],
				'is_following' => $this->getBinaryValue($user['my_follow_id']),
			];
	}
}