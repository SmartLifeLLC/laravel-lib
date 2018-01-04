<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 16:09
 */

namespace App\ValueObject;


/**
 * Class MakeFollowResultVO
 * @package App\ValueObject
 */
class SwitchFollowResultVO
{

    private $isFirstTime = false;
    private $isFollowOn = false;

    /**
     * @return bool
     */
    public function isFirstTime(): bool
    {
        return $this->isFirstTime;
    }

    /**
     * @return bool
     */
    public function isFollowOn(): bool
    {
        return $this->isFollowOn;
    }

    /**
     * SwitchFollowResultVO constructor.
     * @param $isFirstTime
     * @param $isFollowOn
     */
    public function __construct($isFirstTime,$isFollowOn)
    {
        $this->isFirstTime = $isFirstTime;
        $this->isFollowOn = $isFollowOn;
    }
}