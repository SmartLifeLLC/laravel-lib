<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 21:11
 */

namespace App\Http\JsonView\User;


use App\Http\JsonView\JsonResponseView;
use App\Lib\Util;
use App\ValueObject\FollowOrFollowerGetListResultVO;

class FollowOrFollowerGetListJsonView extends JsonResponseView
{

	/**
	 * @var FollowOrFollowerGetListResultVO
	 */
	protected $data;
	function createBody()
	{
		$list = [];
		foreach($this->data->getList() as $entity){
			$user = [];
			$user['id'] = $entity->user_id;
			$user['user_name'] = $entity->user_name;
			$user['profile_image_url'] = Util::getS3CdnUrl($entity->s3_key);
			$user['introduction'] = $entity->description;
			$user['is_following'] = ($entity->is_follow == 1)?1:0;
			$list [] = $user;
		}

		$this->body =
			[
			'counts'=>[
				'contribution'=>$this->data->getCountContribution(),
				'interest'=>$this->data->getCountInterest(),
				'follow'=>$this->data->getCountFollow(),
				'follower'=>$this->data->getCountFollower()],
			'has_next'=>(int) $this->data->getHasNext(),
			'list'=>$list
		];
	}


}