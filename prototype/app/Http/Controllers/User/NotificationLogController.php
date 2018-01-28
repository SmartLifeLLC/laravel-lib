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
use Illuminate\Http\Request;

class NotificationLogController extends Controller
{

	/**
	 * @param Request $request
	 * @param $boundaryId
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function getList(Request $request,$boundaryId = null){
    	if($boundaryId == null || $boundaryId == "undefined")  $boundaryId = 0;
	    $limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
	    $orderTypeString = $request->get('order',QueryOrderTypes::DESCENDING);
	    $listType = $request->get('listType','user');
        $userId = $this->getCurrentUserId();
        $serviceResult = (new NotificationLogService())->getList($userId,$boundaryId,$listType,$limit,$orderTypeString);
        return $this->responseJson(new GetLogsJsonView($serviceResult));
    }
}