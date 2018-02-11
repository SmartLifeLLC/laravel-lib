<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 21:45
 */

namespace App\Http\Controllers\User;


use App\Constants\DefaultValues;
use App\Constants\ListType;
use App\Constants\QueryOrderTypes;
use App\Http\Controllers\Controller;
use App\Http\JsonView\User\NotificationLog\GetAdminNotificationLogsJsonView;
use App\Models\CurrentUser;
use App\Models\NotificationLog;
use App\Services\NotificationLogService;
use App\Http\JsonView\User\NotificationLog\GetUserNotificationLogsJsonView;
use Illuminate\Http\Request;

class NotificationLogController extends Controller
{

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function getList(Request $request){
	    $limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
	    $page = $request->get('page',DefaultValues::QUERY_DEFAULT_PAGE);
	    $boundaryId = $request->get('boundary_id',0);
	    $orderTypeString = $request->get('order',QueryOrderTypes::DESCENDING);
	    $listType = $request->get('list_type','user');
        $userId = $this->getCurrentUserId();
        if($listType == ListType::NOTIFICATION_LOG_USER) {
	        $serviceResult = (new NotificationLogService())->getUserNotificationList($userId, $boundaryId, $limit, $orderTypeString);
	        return $this->responseJson(new GetUserNotificationLogsJsonView($serviceResult));
        }else {
	        $serviceResult = (new NotificationLogService())->getAdminNotificationList($userId, $limit, $page);
	        return $this->responseJson(new GetAdminNotificationLogsJsonView($serviceResult));
        }
    }
}



