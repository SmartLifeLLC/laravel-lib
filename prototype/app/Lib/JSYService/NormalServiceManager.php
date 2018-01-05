<?php
/**
 * class NonTransactionService
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/01
 */
namespace App\Lib\JSYService;
class NormalServiceManager extends ServiceManager
{
    /**
     *
     */
    final protected function beforeExecute()
    {

    }

    /**
     *
     */
    final protected function afterExecute()
    {

    }

    /**
     * @param \Exception $exception
     * @throws \Exception
     */
    final protected function errorExecute(\Exception $exception)
    {

    }

    /**
     *
     */
    final protected  function finallyExecute()
    {
        // TODO: Implement finallyExecute() method.
    }

}