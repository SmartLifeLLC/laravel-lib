<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 12:23
 */

namespace App\Constants;


use App\Lib\Singleton;

class CurrentUser
{
    use Singleton;

    private $auth;

    private $userId;

    /**
     * @return mixed
     */
    public function getUserId():Int
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getAuth():String
    {
        return $this->auth;
    }

    /**
     * @param mixed $auth
     */
    public function setAuth($auth): void
    {
        $this->auth = $auth;
    }

}