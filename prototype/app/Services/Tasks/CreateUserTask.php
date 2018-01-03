<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2017/12/03
 * Time: 23:10
 * よくないタスク。一つのタスクで多くの作業が行われている。
 * タスク間で共有されるデータに関する設計を見直す必要がある。
 */

namespace App\Services\Tasks;


use App\Constants\DefaultValues;
use App\Constants\Gender;
use App\Constants\ImageCategory;
use App\Lib\JSYService\ServiceTask;
use App\Lib\Logger;
use App\Lib\Util;
use App\Models\Images;
use App\Models\User;
use App\ValueObject\FacebookResponseVO;
use DateTime;
use Exception;
use App\ValueObject\UserVO;

class CreateUserTask implements ServiceTask
{

    /**
     * @var User
     */
    private $userModel;
    /**
     * @var Contents
     */
    private $imagesModel;

    /**
     * @var FacebookResponseVO
     */
    private $facebookUserData;

    private $auth;
    private $userId;

    /**
     * CreateUserTask constructor.
     * @param $facebookUserData
     * @param User $userModel
     * @param Images $imagesModel
     */
    public function __construct(FacebookResponseVO $facebookUserData, User $userModel, Images $imagesModel)
    {
        $this->facebookUserData = $facebookUserData;
        $this->userModel = $userModel;
        $this->imagesModel = $imagesModel;
    }

    public function run():UserVO
    {
        // TODO: Implement getTask() method.
            $fbData = $this->facebookUserData;
            $auth = Util::getUniqueAuth();
            $userId = $this->userModel->createUserDataAndGetId($fbData, $auth);

            $this->auth = $auth;
            $this->userId = $userId;
            $userVO = new UserVO();
            try {
                //Image URL
                $profileId = 0;
                $coverId = 0;
                if (!empty($fbData->getProfileUrl())) {
                    $profileUrl = $fbData->getProfileUrl();
                    $profileId = $this->imagesModel->copyImageUrlToS3AndGetId($userId, ImageCategory::PROFILE, $profileUrl);
                }

                if (!empty($fbData->getCoverUrl())) {
                    $coverUrl = $fbData->getCoverUrl();
                    $coverId = $this->imagesModel->copyImageUrlToS3AndGetId($userId, ImageCategory::COVER, $coverUrl);
                }

                //Update User Data
                $this->userModel->updateUserImageData($userId, $profileId, $coverId);
                $userVO->setAuth($this->auth);
                $userVO->setUserId($this->userId);
            }catch (exception $e){
                Logger::serverError($e);
            }finally{
                return $userVO;

            }
    }
}