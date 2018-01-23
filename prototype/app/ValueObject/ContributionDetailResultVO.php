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
	private $product;
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
	public function getProduct()
	{
		return $this->product;
	}

	/**
	 * @return mixed
	 */
	public function getProductCategories()
	{
		return $this->productCategories;
	}

	/**
	 * @return int
	 */
	public function getCommentCount()
	{
		return $this->commentCount;
	}

	/**
	 * ContributionGetDetailResultVO constructor.
	 * @param $contribution
	 * @param $product
	 * @param $productCategories

	 */
	public function __construct($contribution, $product, $productCategories)
	{
		$this->contribution = $contribution;
		$this->product = $product;
		$this->productCategories = $productCategories;
	}
}