<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/30
 * Time: 0:07
 */

namespace App\Http\Controllers\Translate;

use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousUserJsonView;
use App\Models\Old\User;
use App\Services\Translate\PreviousUserService;
use App\Models\Old\UserSetting;
use DB;

class TranslateUserController extends Controller
{
    /**
     * @return array
     */
    public function translatePreviousData(){
        $results = array();

        $users = (new User())->getData();

        foreach ($users as $user) {
            $userData = [
                'facebookId' => $user->facebook_id,
                'mailAddress' => $user->mail_address,
                'auth' => $user->auth,
                'firstName' => $user->first_name,
                'lastName' => $user->family_name,
                'userName' => $user->user_name,
                'birthday' => $user->birthday,
                'gender' => $user->gender,
                'genderPublishedFlag' => $user->gender_published_flag,
                'birthdayPublishedFlag' => $user->birthday_published_flag,
                'workedHistory' => $user->worked_history,
                'profileImageId' => $user->profile_image_id,
                'coverImageId' => $user->cover_image_id,
                'description' => $user->description,
                'created' => $user->created_at,
                'lastPosted' => $user->updated_at
            ];

            $userSetting = (new UserSetting()) -> getData($user->id);
            $userData['followNotification'] = $userSetting->can_notice_follow;
            $userData['haveNotification'] = $userSetting->can_notice_having;
            $userData['likeNotification'] = $userSetting->can_notice_like;
            $userData['interestNotification'] = $userSetting->can_notice_interest;
            $userData['commentNotification'] = $userSetting->can_notice_comment;

            $serviceResult = (new PreviousUserService())->getData($userData);

            $jsonView = (new PreviousUserJsonView($serviceResult));
            $results[] = $this->responseJson($jsonView);
        }
        return $results;
    }
}