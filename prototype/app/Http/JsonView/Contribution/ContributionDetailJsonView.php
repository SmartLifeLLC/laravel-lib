<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 5:51
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;
use App\ValueObject\ContributionDetailResultVO;

class ContributionDetailJsonView extends JsonResponseView
{
	/**
	 * @var ContributionDetailResultVO
	 */
	protected $data;
	function createBody()
	{
		$contribution = $this->data->getContribution();
		$categories = $this->data->getProductCategories();
		$body = $this->getWellFormedContribution($contribution,$categories,$shops=[]);

		$this->body = $body;
	}
}