<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 18:11
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;
use App\ValueObject\ReactionGetLIstResultVO;

class ReactionGetListJsonView extends JsonResponseView
{
	/**
	 * @var ReactionGetLIstResultVO
	 */
	protected $data;
	function createBody()
	{
		$counts = $this->data->getCounts();
		if(empty($counts)){
			$this->body = [
				'counts' =>
					[
						'all'=>0,
						'like'=>0,
						'interest'=>0
					],
				'has_next' => 0,
				'reactions'=> []
			];
			return;
		}

		$hasNext = (int) $this->data->getHasNext();
		$reactions = [];
		foreach($this->data->getReactions() as $userReaction){
			$userReaction = (array) $userReaction;
			$reactions[] =
				[
					'user'=> [
						'id' => $userReaction['id'],
						'user_name' => $userReaction['user_name'],
						'profile_image_url' => $this->getImageURLForS3Key($userReaction['profile_image_s3_key']),
						'is_following' => $userReaction['is_following'],
						'introduction' => $userReaction['description']
					],
					'type'=> $userReaction['contribution_reaction_type']
				];


		}

		$this->body = [
			'counts' =>
				[
					'all'=>$counts->total_reaction_count,
					'like'=>$counts->like_reaction_count,
					'interest'=>$counts->interest_reaction_count,
				],
			'has_next' => $hasNext,
			'reactions'=> $reactions

		];
	}

}