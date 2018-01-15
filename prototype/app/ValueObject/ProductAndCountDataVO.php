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
     * @var Array
     */
    private $products;

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return Array
     */
    public function getProducts(): Array
    {
        return $this->products;
    }


    /**
     * ProductAndCountDataVO constructor.
     * @param $count
     * @param array $products
     */
    public function __construct($count,Array $products)
    {
        $this->count = $count;
        $this->products = $products;
    }
}