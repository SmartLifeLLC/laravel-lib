<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 16:10
 */

namespace App\Lib\JSYService;


class ServiceManagerFactory
{

    /**
     * @param bool $needTransaction
     * @return ServiceManager
     */
    public final static function create(bool $needTransaction = false):ServiceManager{
        if($needTransaction)
            return new TransactionServiceManager();
        else
            return new NormalServiceManager();
    }
}