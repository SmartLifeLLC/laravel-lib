<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 5:42
 */

namespace App\ValueObject;


class ContributionDetailResultVO
{
	private $contribution;
	private $productCategories;
	/**
	 * @return mixed
	 */
	public function getContribution()
	{
		return $this->contribution;
	}

	/**
	 * @return mixed
	 */
	public function getProductCategories()
	{
		return $this->productCategories;
	}


	/**
	 * ContributionGetDetailResultVO constructor.
	 * @param $contribution
	 * @param $productCategories

	 */
	public function __construct($contribution,$productCategories)
	{
		$this->contribution = $contribution;
		$this->productCategories = $productCategories;
	}
}