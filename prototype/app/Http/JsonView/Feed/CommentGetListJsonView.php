<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 20:17
 */

namespace App\Http\JsonView\Feed;


use App\Constants\ConfigConstants;
use App\Constants\DateTimeFormat;
use App\Constants\Gender;
use App\Http\JsonView\JsonResponseView;


class CommentGetListJsonView extends JsonResponseView
{
	/**
	 * @var array of FeedCommentEntity
	 */
	protected $data;
	function createBody()
	{
		$body = [] ;
		foreach ($this->data as $entity){
			$gender = ($entity->gender_published_flag)?Gender::getString($entity->gender):"";
			$birthday = ($entity->birthday_published_flag)?DateTimeFormat::getBirthdayFromFullDate($entity->birthday):"";
			$comment =
				[
					'id'                    => $entity->id,
					'product_item_id'       => $entity->product_id,
					'user_id'               => $entity->user_id,
					'review_post_id'        => $entity->feed_id,
					'text'                  => $entity->content,
					'created_at'            => $entity->created_at,
					'updated_ad'            => $entity->updated_at,
					'post_user_info'=>
							[
								'user_id'           => $entity->user_id,
								'user_name'         => $entity->user_name,
								'profile_image_url' => (empty($entity->profile_image_s3_key))?"":ConfigConstants::getCdnHost().$entity->profile_image_s3_key,
								'cover_image_url'   => (empty($entity->cover_image_s3_key))  ?"":ConfigConstants::getCdnHost().$entity->cover_image_s3_key,
								'introduction'      => $entity->introduction,
								'gender'            => $gender,
								'birthday'          => $birthday
							]
				];
			$body[] = $comment;
		}
		$this->body = $body;
	}

}