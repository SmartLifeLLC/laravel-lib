<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 12:07
 * License : https://qiita.com/YusukeHigaki/items/36baa45851fd37bf56b8
 */

namespace App\Lib;

trait Singleton
{
    /**
     * @var array
     */
    private static $instance = [];
    private function __construct()
    {
    }

    /**
     * @return mixed
     */
    public static function shared()
    {
        $class = get_called_class();
        if (!isset(self::$instance[$class])) self::$instance[$class] = new $class;

        return self::$instance[$class];
    }

    /**
     * @throws \Exception
     */
    public final function __clone()
    {
        throw new \Exception('Clone is not allowed against' . get_class($this));
    }
}