<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 18:11
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;
use App\ValueObject\ReactionGetListResultVO;

class ReactionGetListJsonView extends JsonResponseView
{
	/**
	 * @var ReactionGetListResultVO
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
			$reactions[] =
				[
					'ur'=>
						$this->getUserHashArray(
							$userReaction['id'], //id
							$userReaction['user_name'], //user_name
							$this->getImageURLForS3Key($userReaction['profile_image_s3_key']), //profile_image_url
							$userReaction['is_following'],//is_following
							$this->$userReaction['description']
						),
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