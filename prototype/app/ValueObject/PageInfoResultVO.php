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
	private $contributionCount;
	private $friendsCount;
	private $allReactionCounts;

	/**
	 * PageInfoResultVO constructor.
	 * @param $userInfoForPage
	 * @param $contributionCount
	 * @param $friendsCount
	 * @param $allReactionCounts
	 */
	public function __construct($userInfoForPage, $contributionCount, $friendsCount, $allReactionCounts)
	{
		$this->userInfoForPage = $userInfoForPage;
		$this->contributionCount = $contributionCount;
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
	public function getContributionCount()
	{
		return $this->contributionCount;
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