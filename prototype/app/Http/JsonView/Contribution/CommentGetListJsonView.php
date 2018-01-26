<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 20:17
 */

namespace App\Http\JsonView\Contribution;


use App\Constants\SystemConstants;
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
			$gender =   $this->getGenderString($entity->gender,$entity->gender_published_flag);
			$birthday = $this->getBirthdayString($entity->birthday,$entity->birthday_published_flag);
			$comment =
				[
					'id'                    => $entity->id,
					'product_item_id'       => $entity->product_id,
					'user_id'               => $entity->user_id,
					'contribution_id'        => $entity->contribution_id,
					'text'                  => $entity->content,
					'created_at'            => $entity->created_at,
					'updated_ad'            => $entity->updated_at,
					'post_user_info'=>
							[
								'user_id'           => $entity->user_id,
								'user_name'         => $entity->user_name,
								'profile_image_url' => $this->getImageURLForS3Key($entity->profile_image_s3_key),
								'cover_image_url'   => $this->getImageURLForS3Key($entity->cover_image_s3_key),
								'introduction'      => $entity->description,
								'gender'            => $gender,
								'birthday'          => $birthday
							]
				];
			$body[] = $comment;
		}
		$this->body = $body;
	}

}