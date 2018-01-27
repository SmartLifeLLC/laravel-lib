<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 23:38
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;
use App\ValueObject\ContributionListResultVO;

class ContributionListJsonView extends JsonResponseView
{
	/**
	 * @var ContributionListResultVO
	 */
	protected $data;
	function createBody()
	{


		$contributions = $this->data->getContributions();


		$categories = $this->data->getProductsCategories();

		$productsCategories = [];
		foreach($categories as $category){
			$productsCategories[$category['product_id']][$category['product_category_id']] =
				[
					'product_category_id' => $category['product_category_id'],
					'name' => $category['name'],
					'breadcrumb' => $category['unique_name']
				];

		}

		$body =[];
		foreach($contributions as $contribution){
			$displayData =
                $this->getWellFormedContribution($contribution,array_values($productsCategories[$contribution['product_id']]),$shops=[]);
			$body[] = $displayData;
		}
		$this->body = $body;
	}

}