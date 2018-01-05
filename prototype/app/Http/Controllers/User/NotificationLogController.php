<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 21:45
 */

namespace App\Http\Controllers\User;


use App\Constants\DefaultValues;
use App\Constants\QueryOrderTypes;
use App\Http\Controllers\Controller;
use App\Models\CurrentUser;
use App\Services\NotificationLogService;
use App\Http\JsonView\User\NotificationLog\GetLogsJsonView;
class NotificationLogController extends Controller
{

    /**
     * @param int $boundaryId
     * @param int $limit
     * @param string $orderTypeString
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(int $boundaryId = 0, int $limit = DefaultValues::QUERY_DEFAULT_LIMIT, $orderTypeString = QueryOrderTypes::DESCENDING){

        if(!is_numeric($boundaryId)) return $this->responseParameterErrorJsonViewWithDebugMessage("Boundary id {$boundaryId} is not numeric.");
        if(!is_numeric($limit)) return $this->responseParameterErrorJsonViewWithDebugMessage("Limit {$limit} is not numeric. ");
        if($orderTypeString != QueryOrderTypes::DESCENDING && $orderTypeString != QueryOrderTypes::ASCENDING)
            return $this->responseParameterErrorJsonViewWithDebugMessage("Order type {$orderTypeString} is not valid value. ");
        $userId = CurrentUser::shared()->getUserId();
        $serviceResult = (new NotificationLogService())->getLogs($userId,$boundaryId,$limit,$orderTypeString);
        return $this->responseJson(new GetLogsJsonView($serviceResult));
    }
}