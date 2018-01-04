<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 15:21
 */

class Value {
    protected $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}

$three = new Value(3);
$four = new Value(4);

$closure = function ($delta) { var_dump($this) ;  var_dump($delta); };
$closure->call($three, 4);
$closure->call($four, 4);
?>