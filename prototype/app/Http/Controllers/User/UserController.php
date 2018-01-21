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
use App\Http\JsonView\User\Page\PageInfoJsonView;
use App\Http\JsonView\User\User\NotificationSettingJsonView;
use App\Http\JsonView\User\User\UserEditJsonView;
use App\Http\JsonView\User\User\UserInfoJsonVIew;
use App\Lib\JSYService\ServiceResult;
use App\Models\CurrentUser;
use App\Services\UserService;
use App\ValueObject\UserEditVO;
use App\ValueObject\UserNotifyPropertiesVO;
use Illuminate\Http\Request;
use Ramsey\Uuid\Builder\DefaultUuidBuilder;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo(){
        $userId = $this->getCurrentUserId();
        $serviceResult = (new UserService())->getUserInfo($userId);
        $jsonView = new UserInfoJsonVIew($serviceResult);
        return $this->responseJson($jsonView);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationSettings(){
        $userId = $this->getCurrentUserId();
        $serviceResult = (new UserService())->getNotificationSettings($userId);
        $jsonView = new NotificationSettingJsonView($serviceResult);
        return $this->responseJson($jsonView);
    }


    public function edit(Request $request){
		$userId = $this->getCurrentUserId();
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

	/**
	 * @param $ownerId
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function pageInfo($ownerId = null){
	    $userId = $this->getCurrentUserId();
		if($ownerId == null)
			$ownerId = $userId;
	    $serviceResult = (new UserService())->getPageInfo($userId,$ownerId);
	    return $this->responseJson(new PageInfoJsonView($serviceResult));
    }

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function editNotifyProperties(Request $request)
    {
	    $userId = $this->getCurrentUserId();
	    $saveData = new UserNotifyPropertiesVO();
	    $saveData->setIsPermittedComment($request->can_notice_comment);
	    $saveData->setIsPermittedFollow($request->can_notice_follow);
	    $saveData->setIsPermittedHave($request->can_notice_having);
	    $saveData->setIsPermittedInterest($request->can_notice_interest);
	    $saveData->setIsPermittedLike($request->can_notice_like);
		$serviceResult = (new UserService())->updateNotifyProperties($userId,$saveData);
		return $this->responseJson(new NotificationSettingJsonView($serviceResult));
    }

    public function interestList(Request $request, $ownerId = null){
    	$userId = $this->getCurrentUserId();
    	if($ownerId == null)
    	    $ownerId = $userId;




    }


}