<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 18:09
 */

namespace App\Http\Controllers\User;


use App\Constants\DefaultValues;
use App\Constants\ListType;
use App\Constants\StatusCode;
use App\Http\Controllers\Controller;
use App\Constants\PostParametersValidationRule;
use App\Http\JsonView\JsonResponseErrorView;
use App\Http\JsonView\User\FollowOrFollowerGetListJsonView;
use App\Models\CurrentUser;
use App\Lib\Logger;
use App\Services\FriendService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Http\JsonView\User\Follow\SwitchUserFollowStatusJsonView;
use Illuminate\Support\Facades\Log;

class FriendController extends Controller
{



    /**
     * @deprecated
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function followUser(Request $request)
    {
        $validator = $this->createValidator( $request->all(), PostParametersValidationRule::TARGET_USER_ID) ;
        if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);
        return $this->switchUserFollowStatus($request->to,1);
    }


    /**
     * @deprecated
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function followCancel(Request $request)
    {
        $validator = $this->createValidator( $request->all(), PostParametersValidationRule::TARGET_USER_ID) ;
        if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);
        return $this->switchUserFollowStatus($request->to,0);
    }


    /**
     * @param $targetUserId
     * @param $isFollowOn
     * @return \Illuminate\Http\JsonResponse
     */
    public function switchUserFollowStatus($targetUserId,$isFollowOn){
        if(!is_numeric($targetUserId)){
            return $this->responseParameterErrorJsonViewWithDebugMessage("Target user id {$targetUserId}  is not valid value.");
        }

        if(!(is_numeric($isFollowOn)) ){
            return $this->responseParameterErrorJsonViewWithDebugMessage("Block on off value {$isFollowOn}  is not valid value.");
        }

        $userId = $this->getCurrentUserId();
        if($userId == $targetUserId){
            return $this->responseParameterErrorJsonViewWithDebugMessage("User id {$userId} is same with target user id {$targetUserId}");
        }

        $serviceResult = (new FriendService())->switchFollowStatus($userId,$targetUserId,$isFollowOn);
	    $jsonView = new SwitchUserFollowStatusJsonView($serviceResult);
        if($serviceResult->getResult() == null){
        	return $this->responseJson($jsonView);
        }
        $switchFollowStatusVO = $serviceResult->getResult();

        //todo : send notification;
        if($switchFollowStatusVO->isFirstTime()){
	        (new NotificationService())->sendNotification();
        }

        return $this->responseJson($jsonView);
    }


	/**
	 * @param Request $request
	 * @param null $ownerId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getFriendList(Request $request,$ownerId = null){
		if($ownerId == 0 || $ownerId == 'undefined') $ownerId = null;
		$type = $request->get('listType' ,ListType::FRIEND_LIST_FOLLOW);
		if($type == ListType::FRIEND_LIST_FOLLOW){
			return $this->getFollowList($request,$ownerId);
		}else{
			return $this->getFollowerList($request,$ownerId);
		}
	}


	/**
	 * @param Request $request
	 * @param null $ownerId
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function getFollowList(Request $request,$ownerId = null){
		$userId = $this->getCurrentUserId();
		if($ownerId == null) $ownerId = $userId;

		$page = $request->get('page',DefaultValues::QUERY_DEFAULT_PAGE);
		$limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
		$serviceResult = (new FriendService())->getFollowList($userId,$ownerId,$page,$limit);
		return $this->responseJson(new FollowOrFollowerGetListJsonView($serviceResult));

    }

	/**
	 * @param Request $request
	 * @param null $ownerId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getFollowerList(Request $request,$ownerId = null){
		$userId = $this->getCurrentUserId();
		if($ownerId == null) $ownerId = $userId;

		$page = $request->get('page',DefaultValues::QUERY_DEFAULT_PAGE);
		$limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
		$serviceResult = (new FriendService())->getFollowerList($userId,$ownerId,$page,$limit);
		return $this->responseJson(new FollowOrFollowerGetListJsonView($serviceResult));
	}


}