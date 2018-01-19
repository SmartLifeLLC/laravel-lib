<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 21:04
 */

namespace App\ValueObject;


class FollowOrFollowerGetListResultVO
{

	private $countContribution;
	private $countInterest;
	private $countFollow;
	private $countFollower;
	private $list;
	private $hasNext;
	/**
	 * @return mixed
	 */
	public function getCountContribution()
	{
		return $this->countContribution;
	}

	/**
	 * @return mixed
	 */
	public function getCountInterest()
	{
		return $this->countInterest;
	}

	/**
	 * @return mixed
	 */
	public function getCountFollow()
	{
		return $this->countFollow;
	}

	/**
	 * @return mixed
	 */
	public function getCountFollower()
	{
		return $this->countFollower;
	}

	/**
	 * @return mixed
	 */
	public function getList()
	{
		return $this->list;
	}

	/**
	 * @return mixed
	 */
	public function getHasNext()
	{
		return $this->hasNext;
	}

	/**
	 * FollowGetListResultVO constructor.
	 * @param $countContribution
	 * @param $countInterest
	 * @param $countFollow
	 * @param $countFollower
	 * @param $list
	 * @param $hasNext
	 */
	public function __construct($countContribution, $countInterest, $countFollow, $countFollower, $list, $hasNext)
	{
		$this->countContribution = $countContribution;
		$this->countInterest = $countInterest;
		$this->countFollow = $countFollow;
		$this->countFollower = $countFollower;
		$this->list = $list;
	}

}