<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 23:20
 */

namespace App\Http\JsonView\Feed;


use App\Http\JsonView\JsonResponseView;

class ContributionEditJsonView extends JsonResponseView
{
	/**
	 * Data from ContributionService@Edit
	 */
	function createBody()
	{
		$this->body = $this->data;
	}
}