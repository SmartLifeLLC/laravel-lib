<?php
/**
 * class ServiceTask
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/01
 */
namespace App\Lib\JSYService;
interface ServiceTask
{
    function run();
    function getResult();
}