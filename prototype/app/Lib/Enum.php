<?php
/**
 * class Enum
 * @package App\Lib
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/18
 * Source Link : https://qiita.com/Hiraku/items/71e385b56dcaa37629fe
 */

namespace App\Lib;


/**
 * Class Enum
 * @package App\Lib
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/01
 */
abstract class Enum
{
    /**
     * @var
     */
    private $scalar;

    /**
     * Enum constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $ref = new ReflectionObject($this);
        $consts = $ref->getConstants();
        if (!in_array($value, $consts, true)) {
            throw new InvalidArgumentException;
        }

        $this->scalar = $value;
    }

    /**
     * @param $label
     * @param $args
     * @return mixed
     */
    final public static function __callStatic($label, $args)
    {
        $class = get_called_class();
        $const = constant("$class::$label");
        return new $class($const);
    }


    /**
     * @return mixed
     */
    final public function valueOf()
    {
        return $this->scalar;
    }

    /**
     * @return string
     */
    final public function __toString()
    {
        return (string)$this->scalar;
    }
}