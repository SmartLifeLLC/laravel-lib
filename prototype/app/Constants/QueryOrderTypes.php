<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 14:44
 */

namespace App\Constants;


use App\Lib\Enum;

final class QueryOrderTypes extends Enum
{
    const DESCENDING = "desc";
    const ASCENDING = "asc";

    /**
     * Enum constructor.
     * @param $value
     */
    public function __construct($value)
    {
        $value = mb_strtolower($value);
        parent::__construct($value);
    }

    function getQueryCompareSymbol(){
        return ($this->getValue() == QueryOrderTypes::DESCENDING)?"<":">";
    }

}