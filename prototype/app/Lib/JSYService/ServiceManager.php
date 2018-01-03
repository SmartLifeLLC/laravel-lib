<?php
/**
 * class Service
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/12/31
 */
namespace App\Lib\JSYService;
use App\Constants\StatusCode;
use App\Lib\Logger;
use Log;
abstract class ServiceManager
{
    /**
     * @var \Closure
     */
    private $task;
    private $newthis;
    private $serviceResult;
    protected abstract function beforeExecute();
    protected abstract function afterExecute();
    protected abstract function finallyExecute();
    protected abstract function errorExecute(\Exception $exception);

    public function setTask(\Closure $task,$newthis){
        $this->task = $task;
        $this->newthis = $newthis;
    }
    private function runTask(){
        $this->serviceResult = $this->task->call($this->newthis);
    }

    public function getServiceResult():ServiceResult{
        return $this->serviceResult;
    }

    public final function execute(){
        $this->beforeExecute();
        try {
            $this->runTask();
            $this->afterExecute();
        }catch (\Exception $exception){
            $this->serviceResult = new ServiceResult(null,StatusCode::UNKNOWN,$exception->getMessage());
            Logger::serverError($exception);
            $this->errorExecute($exception);
        }finally{
            $this->finallyExecute();
        }
    }


}