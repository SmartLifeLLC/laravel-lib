<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 23:40
 */

namespace App\ValueObject;


class ContributionListResultVO
{
	private $feeds;
	private $productsCategories;

	/**
	 * ContributionListResultVO constructor.
	 * @param $feeds
	 * @param $productCategories
	 */
	public function __construct($feeds, $productCategories)
	{
		$this->feeds = $feeds;
		$this->productsCategories = $productCategories;
	}

	/**
	 * @return mixed
	 */
	public function getFeeds()
	{
		return $this->feeds;
	}

	/**
	 * @return mixed
	 */
	public function getProductsCategories()
	{
		return $this->productsCategories;
	}



}