<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 11:39
 */

namespace App\Http\Controllers\User;


use App\Constants\DefaultValues;
use App\Http\JsonView\User\Block\BlockListJsonView;
use App\Models\CurrentUser;
use App\Constants\PostParametersValidationRule;
use App\Http\Controllers\Controller;
use App\Services\BlockService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Validator;
use App\Http\JsonView\User\Block\SwitchUserBlockStatusJsonView;
class BlockController extends Controller
{

    /**
     * @deprecated
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function blockUser(Request $request){

        //パラメータチェック
        $validator = $this->createValidator( $request->all(), PostParametersValidationRule::TARGET_USER_ID) ;
        if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);
        return $this->switchUserBlockStatus($request->to,1);
    }

    /**
     * @deprecated
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelBlock(Request $request){
        //パラメータチェック
        $validator = $this->createValidator( $request->all(), PostParametersValidationRule::TARGET_USER_ID) ;
        if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);
        return $this->switchUserBlockStatus($request->to,0);
    }


    /**
     * @param $targetUserId
     * @param $isBlockOn
     * @return \Illuminate\Http\JsonResponse
     */
    public function switchUserBlockStatus($targetUserId, $isBlockOn){
        if(!is_numeric($targetUserId)){
            return $this->responseParameterErrorJsonViewWithDebugMessage("Target user id {$targetUserId}  is not valid value ");
        }

        if(!(is_numeric($isBlockOn)) ){
            return $this->responseParameterErrorJsonViewWithDebugMessage("Block on off value {$isBlockOn}  is not valid value ");
        }

        $userId = CurrentUser::shared()->getUserId();
        $serviceResult = (new BlockService())->switchBlockStatus($userId,$targetUserId,$isBlockOn);

        $jsonView = new SwitchUserBlockStatusJsonView($serviceResult);
        return $this->responseJson($jsonView);
    }

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getList(Request $request){
		$page = $request->get('page',DefaultValues::QUERY_DEFAULT_PAGE);
		$limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
		$userId = CurrentUser::shared()->getUserId();
		$serviceResult = (new BlockService())->getList($userId,$page,$limit);
		return $this->responseJson(new BlockListJsonView($serviceResult));

	}
}