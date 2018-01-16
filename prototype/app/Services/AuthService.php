<?php
/**
 * class AuthService
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/01
 */
namespace App\Services;
use App\Constants\HttpMethod;
use App\Constants\StatusCode;
use App\Constants\UserLimitationLevel;
use App\Lib\JSYService\ServiceManagerFactory;
use App\Lib\JSYService\ServiceResultBoolean;
use App\Lib\JSYService\TransactionServiceManager;
use App\Lib\JSYService\ServiceResult;
use App\Lib\Logger;
use App\Models\Image;
use App\Models\User;
use App\Services\Tasks\CheckFacebookTokenTask;
use App\Services\Tasks\CreateUserTask;
use App\ValueObject\UserValidationVO;

/**
 * Class AuthService
 * @package App\Services
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */
class AuthService extends BaseService
{

	/**
	 * @param $userId
	 * @param $auth
	 * @param $requestType
	 * @return ServiceResult
	 */
    public function isValidUser($userId, $auth, $requestType): ServiceResult
    {
        return $this->executeTasks($this->_isValidUser($userId,$auth,$requestType));
    }

	/**
	 * @param $userId
	 * @param $auth
	 * @param $requestType
	 * @return \Closure
	 */
    private function _isValidUser($userId, $auth, $requestType)
    {
        return
            function () use ($userId, $auth, $requestType) {
                $userValidationVO = (new User())->getUserValidationByUserId($userId);
				var_dump($userValidationVO->getUserId());
                if($userValidationVO == null || $userValidationVO->getAuth() != $auth) {
	                $debugMessage = ($userValidationVO == null) ? "User for {$userId} does not exist. " : "Auth does not matched :  {$userValidationVO->getAuth()} vs {$auth} ";
	                $statusCode = StatusCode::AUTH_FAILED;
	                $serviceResult = ServiceResult::withError($statusCode, $debugMessage);
                }else if($this->_isLimitedUser($requestType,$userValidationVO->getLimitationLevel())){
	                $debugMessage = "User {$userId} has limited for the current http action {$requestType}. ";
	                $statusCode = StatusCode::LIMITED_USER;
	                $serviceResult = ServiceResult::withError($statusCode, $debugMessage);
                }else {

                    $serviceResult = ServiceResult::withResult(true, null);
                }

                return $serviceResult;
            };
    }

	/**
	 * @param $requestType
	 * @param $userLimitationLevel
	 * @return bool
	 */
    private function _isLimitedUser($requestType,$userLimitationLevel){
    	if($userLimitationLevel == UserLimitationLevel::NO_LIMIT)
    		return false;

    	if($userLimitationLevel == UserLimitationLevel::LIMITED_ALL )
    		return true;

    	switch (mb_strtoupper($requestType)){
		    case  HttpMethod::POST:
		    	if(
		    		$userLimitationLevel == UserLimitationLevel::LIMITED_POST_PUT ||
				    $userLimitationLevel == UserLimitationLevel::LIMITED_POST)return true;
		    	break;
		    case HttpMethod::PUT:
			    if($userLimitationLevel == UserLimitationLevel::LIMITED_POST_PUT ||
				    $userLimitationLevel == UserLimitationLevel::LIMITED_PUT)return true;
	        break;

		    default :
		    	return false;
	    }
	}

    /**
     * @param $facebookId
     * @param $facebookToken
     * @return ServiceResult (data is instance of UserValidationVO)
     */
    public function getUserAuth($facebookId, $facebookToken): ServiceResult
    {
        return $this->executeTasks($this->_getUserAuth($facebookId,$facebookToken),true);
    }

    /**
     * @param $facebookId
     * @param $facebookToken
     * @return \Closure
     */
    private function _getUserAuth($facebookId, $facebookToken):\Closure{
        return
        function () use($facebookId,$facebookToken) : ServiceResult{

            $resultClass = UserValidationVO::class;
            $facebookResponseVO = (new CheckFacebookTokenTask($facebookId,$facebookToken)) -> run();

            //Facebook API ERROR
            if(!empty($facebookResponseVO->getError())){
                $serviceResult = ServiceResult::withError(StatusCode::FACEBOOK_TOKEN_API_ERROR, $facebookResponseVO->getError());
                Logger::serviceError(compact('facebookId','facebookToken'),$facebookResponseVO->getError());
                return $serviceResult;

                //Facebook ID does not match
            }else if($facebookResponseVO->getFacebookId() != $facebookId){
                $realFacebookId = $facebookResponseVO->getFacebookId();
                $debugMessage = "ID does not match. Request ID : ".$facebookId." Facebook response : ".$realFacebookId;
                $serviceResult = ServiceResult::withError(StatusCode::FACEBOOK_ID_DOES_NOT_MATCH, $debugMessage);
                Logger::serviceError(compact('facebookId','facebookToken','realFacebookId'),$debugMessage);
                return $serviceResult;
            }

            //Get user auth from database.
            $userModel = new User();
            $imagesModel = new Image();
            $userValidationVO = $userModel->getUserValidationByFacebookId($facebookId);
            //Need to create user data
            if($userValidationVO == null){
                $userValidationVO =  (new CreateUserTask($facebookResponseVO,$userModel,$imagesModel))->run();
            }
            return ServiceResult::withResult($userValidationVO,$resultClass);
        };
    }
}