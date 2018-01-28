<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 23:38
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;


class ContributionFindJsonView extends JsonResponseView
{
	/**
	 * @var \stdClass || null (Contribution)
	 */
	protected $data;
	function createBody()
	{
		if(empty($this->data['entity'])){
			$this->body = [
				'is_contributed' => 0,
				'contribution_id' => 0
			];
		}else{
			$this->body = [
				'is_contributed' => 1,
				'contribution_id' => $this->data['entity']->id
			];
		}
	}

}