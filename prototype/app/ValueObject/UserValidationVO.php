<?php
/**
 * class User
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */
namespace App\ValueObject;

class UserValidationVO
{
    private $userId;
    private $auth;
	private $limitationLevel;


	/**
	 * @param mixed $limitationLevel
	 */
	public function setLimitationLevel($limitationLevel): void
	{
		$this->limitationLevel = $limitationLevel;
	}

	/**
	 * UserValidationVO constructor.
	 * @param $userId
	 * @param $auth
	 * @param $limitationLevel
	 */
    public function __construct($userId,$auth,$limitationLevel)
    {
    	$this->userId = $userId;
    	$this->auth = $auth;
    	$this->limitationLevel = $limitationLevel;
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

	/**
	 * @return mixed
	 */
	public function getLimitationLevel()
	{
		return $this->limitationLevel;
	}

}