<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 20:44
 */

namespace App\Http\JsonView\User\Block;


use App\Http\JsonView\JsonResponseView;

class BlockListJsonView extends JsonResponseView
{
	/**
	 * @var array BlockUser see UserService@blockList
	 */
	protected $data;
	function createBody()
	{
		foreach ($this->data as $blockUser){
			$user = [
				'id' => $blockUser->user_id,
				'introduction' => $blockUser->description,
				'is_following' => 0,
				'is_followerd' => 0,
				'user_name' => $blockUser->user_name,
				'profile_image_user' => $this->getImageURLForS3Key($blockUser->profile_image_s3_key)
			];
			$this->body[] = $user;
		}
	}

}