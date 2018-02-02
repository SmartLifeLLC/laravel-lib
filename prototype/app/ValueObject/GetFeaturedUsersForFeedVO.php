<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/02/02
 * Time: 4:19
 */

namespace App\ValueObject;


class GetFeaturedUsersForFeedVO
{
	private $usersFromFacebookFriends;
	private $usersForPickup;
	/**
	 * @return mixed
	 */
	public function getUsersFromFacebookFriends()
	{
		return $this->usersFromFacebookFriends;
	}

	/**
	 * @return mixed
	 */
	public function getUsersFromPickup()
	{
		return $this->usersForPickup;
	}


	/**
	 * GetFeaturedUsersForFeedVO constructor.
	 * @param $usersForFacebook
	 * @param $usersForPickup
	 */
	public function __construct($usersForFacebook, $usersForPickup)
	{
		$this->usersFromFacebookFriends = $usersForFacebook;
		$this->usersForPickup = $usersForPickup;
	}


}