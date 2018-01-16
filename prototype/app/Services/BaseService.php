<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 15:28
 */

namespace App\Services;


use App\Lib\JSYService\Service;
use App\Lib\JSYService\ServiceManagerFactory;
use App\Lib\JSYService\ServiceResult;

use Symfony\Component\HttpFoundation\File\File;
class BaseService implements Service
{

    /**
     * @param \Closure $tasks
     * @param bool $runTransaction
     * @return ServiceResult
     */
    protected function executeTasks(\Closure $tasks, bool $runTransaction = false) : ServiceResult{
        $manager = ServiceManagerFactory::create($runTransaction);
        $manager->setTasks($tasks,$this);
        $manager->execute();
        return $manager->getServiceResult();
    }
}