<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 18:09
 */

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Constants\ValidateRule;
use App\Constants\CurrentUser;
use App\Lib\Logger;
use App\Services\FollowService;
use Illuminate\Http\Request;
use App\Http\JsonView\User\Follow\SwitchUserFollowStatusJsonView;
use Illuminate\Support\Facades\Log;

class FollowController extends Controller
{


    /**
     * @deprecated
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function followUser(Request $request)
    {
        $validator = $this->createValidator( $request->all(), ValidateRule::TARGET_USER_ID) ;
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
        $validator = $this->createValidator( $request->all(), ValidateRule::TARGET_USER_ID) ;
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

        $userId = CurrentUser::shared()->getUserId();
        if($userId == $targetUserId){
            return $this->responseParameterErrorJsonViewWithDebugMessage("User id {$userId} is same with target user id {$targetUserId}");
        }

        $serviceResult = (new FollowService())->switchFollowStatus($userId,$targetUserId,$isFollowOn);

        $switchFollowStatusVO = $serviceResult->getResult();

        //todo : send notification;
        if($switchFollowStatusVO->isFirstTime()){
            Logger::warning("Implement follow notification system.");
        }
        $jsonView = new SwitchUserFollowStatusJsonView($serviceResult);
        return $this->responseJson($jsonView);
    }

}