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
			$list[] =
				$this->getUserHashArray($entity->user_id,$entity->user_name,$entity->s3_key,$entity->is_follow,$entity->description);

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