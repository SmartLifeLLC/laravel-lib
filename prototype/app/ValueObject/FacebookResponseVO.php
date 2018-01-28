<?php
/**
 * class FacebookResponseVO
 * @package App\ValueObject
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/02
 */

namespace App\ValueObject;


use App\Constants\DefaultValues;
use App\Constants\Gender;
use App\Lib\Util;
use DateTime;

class FacebookResponseVO
{
    private $facebookId;
    private $email;
    private $userName;
    private $lastName;
    private $firstName;
    private $birthdayString;
    private $birthday;
    private $gender;
    private $description;
    private $profileUrl;
    private $coverUrl;
    private $error = null;
    private $fbToken;
    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

	/**
	 * @return mixed
	 */
	public function getFacebookToken()
	{
		return $this->fbToken;
	}

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getBirthdayString()
    {
        return $this->birthdayString;
    }

    /**
     * @return false|string
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getProfileUrl()
    {
        return $this->profileUrl;
    }

    /**
     * @return mixed
     */
    public function getCoverUrl()
    {
        return $this->coverUrl;
    }

    /**
     * @return null
     */
    public function getError()
    {
        return $this->error;
    }

	/**
	 * FacebookResponseVO constructor.
	 * @param $fbData
	 * @param $fbToken
	 */
    public function __construct($fbData,$fbToken)
    {


        if(array_key_exists('error',$fbData)){
            $this->error = $fbData['error'];
            return;
        }
	    $this->fbToken = $fbToken;
        $this->facebookId =  $fbData['id'];
        $this->email = Util::getValueForKey($fbData,"email","");
        $this->userName =  Util::getValueForKey($fbData,"name",null);
        $this->lastName = Util::getValueForKey($fbData,"last_name",null);
        $this->firstName = Util::getValueForKey($fbData,"first_name",null);
        $this->birthdayString = Util::getValueForKey($fbData,"birthday",null);
        $this->birthday = date_format(DateTime::createFromFormat('m/d/Y', $this->birthdayString),'Y-m-d 00:00:00');
        $this->gender = Gender::getInt(Util::getValueForKey($fbData,"gender",null));
        $this->description = mb_substr(Util::getValueForKey($fbData,"about",""), 0, DefaultValues::USER_DESCRIPTION_MAX);
        if (array_key_exists('picture', $fbData)) {
            $this->profileUrl = $fbData['picture']['data']['url'];
        }
        if (array_key_exists('cover', $fbData)) {
            $this->coverUrl = $fbData['cover']['source'];
        }
    }
}