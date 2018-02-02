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
		$welFormedCategories = [];
		foreach($categories as $category){
			$categoryItem = $this->getWelFormedCategory(
				$category['product_category_id'],
				$category['name'],
				$category['unique_name'],
				(int) $category['product_count']
				);
			$welFormedCategories[]=$categoryItem;
		}
		$body = $this->getWellFormedContribution($contribution,$welFormedCategories,$shops=[]);
		$this->body = $body;
	}
}