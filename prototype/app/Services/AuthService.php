<?php
/**
 * class AuthService
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/01
 */
namespace App\Services;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceManagerFactory;
use App\Lib\JSYService\ServiceResultBoolean;
use App\Lib\JSYService\TransactionServiceManager;
use App\Lib\JSYService\ServiceResult;
use App\Lib\Logger;
use App\Models\Images;
use App\Models\User;
use App\Services\Tasks\CheckFacebookTokenTask;
use App\Services\Tasks\CreateUserTask;
use App\ValueObject\UserAuthVO;

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
     * @return ServiceResult
     */
    public function isValidAuth($userId, $auth): ServiceResult
    {
        return $this->executeTasks($this->_isValidAuth($userId,$auth),true);
    }

    /**
     * @param $userId
     * @param $auth
     * @return \Closure
     */
    private function _isValidAuth($userId, $auth)
    {
        return
            function () use ($userId, $auth) {
                $userAuthVO = (new User())->getAuthByUserId($userId);
                if($userAuthVO == null || $userAuthVO->getAuth() != $auth){
                    $debugMessage = ($userAuthVO == null)?"User for {$userId} does not exist. ":"Auth does not matched :  {$userAuthVO->getAuth()} vs {$auth} ";
                    $statusCode = StatusCode::AUTH_FAILED;
                    $serviceResult = ServiceResult::withError($statusCode, $debugMessage);
                }else {
                    $result = true;
                    $serviceResult = ServiceResult::withResult($result, null);
                }

                return $serviceResult;
            };
    }

    /**
     * @param $facebookId
     * @param $facebookToken
     * @return ServiceResult (data is instance of UserVO)
     */
    public function getIdAndAuth($facebookId, $facebookToken): ServiceResult
    {
        return $this->executeTasks($this->_getIdAndAuth($facebookId,$facebookToken),true);
    }

    /**
     * @param $facebookId
     * @param $facebookToken
     * @return \Closure
     */
    private function _getIdAndAuth($facebookId,$facebookToken):\Closure{
        return
        function () use($facebookId,$facebookToken) : ServiceResult{

            $resultClass = UserAuthVO::class;
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
            $imagesModel = new Images();
            $userVO = $userModel->getAuthByFacebookId($facebookId);
            //Need to create user data
            if($userVO == null){
                $userVO =  (new CreateUserTask($facebookResponseVO,$userModel,$imagesModel))->run();
            }
            return ServiceResult::withResult($userVO,$resultClass);
        };
    }
}