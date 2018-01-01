<?php
/**
 * class AuthService
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/01
 */
namespace App\Services;
use App\Lib\JSYService\ServiceResultBoolean;
use App\Lib\JSYService\TransactionServiceManager;
use App\Lib\JSYService\ServiceResult;
use Mockery\Exception;

class AuthService
{
    private $testValue = 10;
    public function isValidAuth($userId,$auth):ServiceResult{
        $manager = new TransactionServiceManager();
        $manager->setTask($this->_isValidAuth($userId,$auth), $this);
        $manager->execute();
        return $manager->getServiceResult();
    }

    private function _isValidAuth($userId,$auth){
        return
        function() use ($userId,$auth) {
            $serviceResult = new ServiceResult(true,200,null);
            return $serviceResult;
        };
    }
}