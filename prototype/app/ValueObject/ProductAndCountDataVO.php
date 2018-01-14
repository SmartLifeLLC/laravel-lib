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
    private $data;

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
    public function getData(): Array
    {
        return $this->data;
    }


    public function __construct($count,Array $data)
    {
        $this->count = $count;
        $this->data = $data;
    }
}