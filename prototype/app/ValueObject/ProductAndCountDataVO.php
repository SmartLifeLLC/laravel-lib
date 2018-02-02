<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/15
 * Time: 1:34
 */

namespace App\ValueObject;


class ProductAndCountDataVO
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var array
     */
    private $products;

	/**
	 * @var array
	 */
    private $productsCategories;

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

	/**
	 * @param $productsCategories
	 */
	public function setProductsCategories($productsCategories){
		$this->productsCategories = $productsCategories;
	}


	/**
	 * @return array
	 */
	public function getProductsCategories(){
		return $this->productsCategories;
	}


    /**
     * ProductAndCountDataVO constructor.
     * @param $count
     * @param array $products
     */
    public function __construct($count,array $products)
    {
        $this->count = $count;
        $this->products = $products;
    }
}