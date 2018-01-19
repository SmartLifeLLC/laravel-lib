<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 18:11
 */

namespace App\Http\JsonView\Feed;


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
		foreach($this->data->getReactions() as $reaction){
			$reactions[] = (array)$reaction;
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