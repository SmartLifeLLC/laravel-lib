<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 19:19
 */

namespace App\ValueObject;


class UserEditVO
{
	private $gender;
	private $birthday;
	private $firstName;
	private $lastName;
	private $userName;
	private $genderPublishedFlag;
	private $birthdayPublishedFlag;
	private $description;
	private $profileImage;
	private $coverImage;
	private $mailAddress;
	private $saveData = [];
	private $address;


	/**
	 * @return mixed
	 */
	public function getGender()
	{
		return $this->gender;
	}

	/**
	 * @param mixed $gender
	 */
	public function setGender($gender): void
	{

		if($gender !== null)
			$this->saveData['gender']=$gender;

		$this->gender = $gender;
	}

	/**
	 * @return mixed
	 */
	public function getBirthday()
	{
		return $this->birthday;
	}

	/**
	 * @param mixed $birthday
	 */
	public function setBirthday($birthday): void
	{
		if(!empty($birthday))
			$this->saveData['birthday'] = $birthday;
		$this->birthday = $birthday;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * @param mixed $firstName
	 */
	public function setFirstName($firstName): void
	{
		if(!empty($firstName))
			$this->saveData['first_name'] = $firstName;
		$this->firstName = $firstName;
	}

	/**
	 * @return mixed
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * @param mixed $lastName
	 */
	public function setLastName($lastName): void
	{
		if(!empty($lastName))
			$this->saveData['last_name'] = $lastName;
		$this->lastName = $lastName;
	}

	/**
	 * @return mixed
	 */
	public function getUserName()
	{
		return $this->userName;
	}

	/**
	 * @param mixed $userName
	 */
	public function setUserName($userName): void
	{
		if(!empty($userName))
			$this->saveData['user_name'] = $userName;
		$this->userName = $userName;
	}

	/**
	 * @return mixed
	 */
	public function getGenderPublishedFlag()
	{
		return $this->genderPublishedFlag;
	}

	/**
	 * @param mixed $genderPublishedFlag
	 */
	public function setGenderPublishedFlag($genderPublishedFlag): void
	{
		if($genderPublishedFlag !== null)
			$this->saveData['gender_published_flag'] = (int) $genderPublishedFlag;
		$this->genderPublishedFlag = $genderPublishedFlag;
	}

	/**
	 * @return mixed
	 */
	public function getBirthdayPublishedFlag()
	{
		return $this->birthdayPublishedFlag;
	}

	/**
	 * @param mixed $birthdayPublishedFlag
	 */
	public function setBirthdayPublishedFlag($birthdayPublishedFlag): void
	{
		if($birthdayPublishedFlag !== null)
			$this->saveData['birthday_published_flag'] = (int) $birthdayPublishedFlag;
		$this->birthdayPublishedFlag = $birthdayPublishedFlag;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description): void
	{
		if(!empty($description))
			$this->saveData['description'] = $description;
		$this->description = $description;
	}

	/**
	 * @param mixed $address
	 */
	public function setAddress($address): void
	{
		if(!empty($address))
			$this->saveData['address'] = $address;
		$this->address = $address;
	}



	/**
	 * @return mixed
	 */
	public function getProfileImage()
	{
		return $this->profileImage;
	}

	/**
	 * @param mixed $profileImage
	 */
	public function setProfileImage($profileImage): void
	{
		$this->profileImage = $profileImage;
	}

	/**
	 * @return mixed
	 */
	public function getCoverImage()
	{
		return $this->coverImage;
	}

	/**
	 * @param mixed $coverImage
	 */
	public function setCoverImage($coverImage): void
	{
		$this->coverImage = $coverImage;
	}

	/**
	 * @return mixed
	 */
	public function getMailAddress()
	{
		return $this->mailAddress;
	}

	/**
	 * @param mixed $mailAddress
	 */
	public function setMailAddress($mailAddress): void
	{
		if(!empty($mailAddress))
			$this->saveData['mail_address'] = $mailAddress;
		$this->mailAddress = $mailAddress;
	}

	/**
	 * @return array
	 */
	public function getSaveData(): array
	{
		return $this->saveData;
	}



	public function __construct(){

	}
}