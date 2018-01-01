<?php
/**
 * class TransactionService
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/01
 */
namespace App\Lib\JSYService;
USE DB;
class TransactionServiceManager extends ServiceManager
{

    final protected function beforeExecute()
    {
        DB::beginTransaction();
    }

    final protected function afterExecute()
    {
        DB::commit();
    }

    final protected function errorExecute(\Exception $exception)
    {

        DB::rollback();
        //ロールバック完了後エラーをスロー
        //Exceptionを放置しないため
        //throw new \Exception($exception->getMessage());
    }


    final public function finallyExecute()
    {
        // TODO: Implement finallyExecute() method.
    }

}