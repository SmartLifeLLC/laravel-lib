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
	private $contributions;
	private $productsCategories;

	/**
	 * ContributionListResultVO constructor.
	 * @param $contributions
	 * @param $productCategories
	 */
	public function __construct($contributions, $productCategories)
	{
		$this->contributions = $contributions;
		$this->productsCategories = $productCategories;
	}

	/**
	 * @return mixed
	 */
	public function getContributions()
	{
		return $this->contributions;
	}

	/**
	 * @return mixed
	 */
	public function getProductsCategories()
	{
		return $this->productsCategories;
	}



}