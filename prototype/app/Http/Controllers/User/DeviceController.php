<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/05
 * Time: 12:55
 */

namespace App\Http\Controllers\User;


use App\Http\JsonView\Device\DeviceRegisterJsonView;
use App\Models\CurrentUser;
use App\Http\Controllers\Controller;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use App\Constants\PostParametersValidationRule;
class DeviceController extends Controller
{

    public function updateToken(Request $request){
        $validator = $this->createValidator(
        	$request->all(),
	        PostParametersValidationRule::NOTIFICATION_TOKEN,
	        PostParametersValidationRule::DEVICE_UUID,
	        PostParametersValidationRule::DEVICE_TYPE) ;
        if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);
        $notificationToken = $request->notification_token;
        $deviceUuid = $request->device_uuid;
        $deviceType = $request->device_type;
        $userId = $this->getCurrentUserId();
        $serviceResult = (new DeviceService())->updateToken($userId,$deviceUuid,$notificationToken,$deviceType);
        $jsonView = new DeviceRegisterJsonView($serviceResult);
        return $this->responseJson($jsonView);
    }
}