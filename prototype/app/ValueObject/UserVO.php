<?php
/**
 * class User
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */
namespace App\ValueObject;

class UserVO
{
    private $userId;
    private $auth;

    public function __construct()
    {
    }

    /**
     * @param mixed $userId
     * @return UserVO
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @param mixed $auth
     * @return UserVO
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getAuth()
    {
        return $this->auth;
    }
}