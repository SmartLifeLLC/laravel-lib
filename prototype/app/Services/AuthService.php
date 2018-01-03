<?php
/**
 * class AuthService
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/01
 */
namespace App\Services;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResultBoolean;
use App\Lib\JSYService\TransactionServiceManager;
use App\Lib\JSYService\ServiceResult;
use App\Lib\Logger;
use App\Models\Images;
use App\Models\User;
use App\Services\Tasks\CheckFacebookTokenTask;
use App\Services\Tasks\CreateUserTask;
use App\ValueObject\UserVO;

/**
 * Class AuthService
 * @package App\Services
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */
class AuthService
{

    /**
     * @param $userId
     * @param $auth
     * @return ServiceResult
     */
    public function isValidAuth($userId, $auth): ServiceResult
    {
        $manager = new TransactionServiceManager();
        $manager->setTask($this->_isValidAuth($userId, $auth), $this);
        $manager->execute();
        return $manager->getServiceResult();
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
                $serviceResult = new ServiceResult(true, 200,null, null);
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
        $manager = new TransactionServiceManager();
        $manager->setTask(
            function () use($facebookId,$facebookToken){
                $facebookResponseVO = (new CheckFacebookTokenTask($facebookId,$facebookToken)) -> run();

                //Facebook API ERROR
                if(!empty($facebookResponseVO->getError())){
                    $serviceResult = new ServiceResult(null, StatusCode::FACEBOOK_TOKEN_API_ERROR,null, $facebookResponseVO->getError());
                    Logger::serviceError(compact('facebookId','facebookToken'),$facebookResponseVO->getError());
                    return $serviceResult;

                //Facebook ID does not match
                }else if($facebookResponseVO->getFacebookId() != $facebookId){
                    $realFacebookId = $facebookResponseVO->getFacebookId();
                    $debugMessage = "ID does not match. Request ID : ".$facebookId." Facebook response : ".$realFacebookId;
                    $serviceResult = new ServiceResult(null, StatusCode::FACEBOOK_ID_DOES_NOT_MATCH, null, $debugMessage);
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
                return new ServiceResult($userVO,StatusCode::SUCCESS,UserVO::class);
            }
            ,$this);
        $manager->execute();
        return $manager->getServiceResult();
    }
}