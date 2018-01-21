<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/06
 * Time: 20:37
 */

namespace App\Http\Controllers\User;


use App\Constants\DefaultValues;
use App\Constants\PostParametersValidationRule;
use App\Http\Controllers\Controller;
use App\Http\JsonView\User\Block\BlockListJsonView;
use App\Http\JsonView\User\User\NotificationSettingJsonView;
use App\Http\JsonView\User\User\UserEditJsonView;
use App\Http\JsonView\User\User\UserInfoJsonVIew;
use App\Lib\JSYService\ServiceResult;
use App\Models\CurrentUser;
use App\Services\UserService;
use App\ValueObject\UserEditVO;
use Illuminate\Http\Request;
use Ramsey\Uuid\Builder\DefaultUuidBuilder;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo(){
        $userId = CurrentUser::shared()->getUserId();
        $serviceResult = (new UserService())->getUserInfo($userId);
        $jsonView = new UserInfoJsonVIew($serviceResult);
        return $this->responseJson($jsonView);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationSettings(){
        $userId = CurrentUser::shared()->getUserId();
        $serviceResult = (new UserService())->getNotificationSettings($userId);
        $jsonView = new NotificationSettingJsonView($serviceResult);
        return $this->responseJson($jsonView);
    }


    public function edit(Request $request){
		$userId = CurrentUser::shared()->getUserId();
	    $validator = $this->createValidator(
	    	$request->all(),
		    PostParametersValidationRule::IMAGE1,
		    PostParametersValidationRule::IMAGE2,
		    PostParametersValidationRule::IMAGE3,
		    PostParametersValidationRule::IMAGE4,
		    PostParametersValidationRule::EMAIL
	    ) ;
	    if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);

	    $userEditVo = new UserEditVO();
	    $userEditVo->setUserName($request->get('user_name',null));
		$userEditVo->setBirthday($request->get('birthday',null));
		$userEditVo->setBirthdayPublishedFlag($request->get('birthday_published_flag',null));
	    $userEditVo->setGender($request->get('gender',null));
	    $userEditVo->setGenderPublishedFlag($request->get('gender_published_flag',null));
		$userEditVo->setMailAddress($request->get('mail_address',null));
		$userEditVo->setDescription($request->get('description',null));
		$userEditVo->setCoverImage($request->get('cover_image',null));
		$userEditVo->setProfileImage($request->get('profile_image',null));
	    $serviceResult = (new UserService())->edit($userId,$userEditVo);
	    return $this->responseJson(new UserEditJsonView($serviceResult));

    }


}