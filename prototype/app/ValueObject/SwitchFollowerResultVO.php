<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 16:15
 */

namespace App\ValueObject;


class SwitchFollowerResultVO
{

    private $isFirstTime = false;

    /**
     * @return bool
     */
    public function isFirstTime(): bool
    {
        return $this->isFirstTime;
    }

    public function __construct($isCreatedIsFirstTime)
    {
        $this->isFirstTime = $isCreatedIsFirstTime;
    }
}