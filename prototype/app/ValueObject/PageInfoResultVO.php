<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/22
 * Time: 2:07
 */

namespace App\ValueObject;


class PageInfoResultVO
{
	private $userInfoForPage ;
	private $feedCount;
	private $friendsCount;
	private $allReactionCounts;

	/**
	 * PageInfoResultVO constructor.
	 * @param $userInfoForPage
	 * @param $feedCount
	 * @param $friendsCount
	 * @param $allReactionCounts
	 */
	public function __construct($userInfoForPage, $feedCount, $friendsCount, $allReactionCounts)
	{
		$this->userInfoForPage = $userInfoForPage;
		$this->feedCount = $feedCount;
		$this->friendsCount = $friendsCount;
		$this->allReactionCounts = $allReactionCounts;
	}

	/**
	 * @return mixed
	 */
	public function getUserInfoForPage()
	{
		return $this->userInfoForPage;
	}

	/**
	 * @return mixed
	 */
	public function getFeedCount()
	{
		return $this->feedCount;
	}

	/**
	 * @return mixed
	 */
	public function getFriendsCount()
	{
		return $this->friendsCount;
	}

	/**
	 * @return mixed
	 */
	public function getAllReactionCounts()
	{
		return $this->allReactionCounts;
	}


}