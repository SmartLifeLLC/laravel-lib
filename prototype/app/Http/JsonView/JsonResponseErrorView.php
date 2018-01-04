<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/03
 * Time: 18:56
 */

namespace App\Http\JsonView;


use App\Constants\StatusMessage;
use App\Lib\JSYService\ServiceResult;

class JsonResponseErrorView extends JsonResponseView
{

    function __construct($statusCode, $debugMessage)
    {
        $serviceResult = ServiceResult::withError($statusCode,$debugMessage);
        parent::__construct($serviceResult);

    }

    //Nothing to do for error view.
    function createBody()
    {

    }
}