<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 5:42
 */

namespace App\ValueObject;


class ContributionGetDetailResultVO
{
	private $feed;
	private $product;
	private $productCategories;
	private $commentCount;
	/**
	 * @return mixed
	 */
	public function getFeed()
	{
		return $this->feed;
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
	 * @param $feed
	 * @param $product
	 * @param $productCategories
	 * @param $commentCount
	 */
	public function __construct($feed,$product,$productCategories,$commentCount)
	{
		$this->feed = $feed;
		$this->product = $product;
		$this->productCategories = $productCategories;
		$this->commentCount = $commentCount;
	}
}