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
			$user =
				$this->getUserHashArray(
					$blockUser->user_id, //id
					$blockUser->user_name, //user_name
					$this->getImageURLForS3Key($blockUser->profile_image_s3_key), //profile_image_url
					$this->getBinaryValue(0),//is_following
					$this->getNotNullString($this->getNotNullString($blockUser->description))
				);
			$this->body[] = $user;
		}
	}

}