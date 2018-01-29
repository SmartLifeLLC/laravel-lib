<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/19
 * Time: 17:45
 */

namespace App\ValueObject;


class ReactionGetListResultVO
{


	/**
	 * @var arrya
	 */
	private $counts;
	/**
	 * @var array
	 */
	private $reactions;

	/**
	 * @var boolean
	 */
	private $hasNext;

	/**
	 * @return mixed
	 */
	public function getCounts()
	{
		return $this->counts;
	}

	/**
	 * @return mixed
	 */
	public function getReactions()
	{
		return $this->reactions;
	}

	/**
	 * @return mixed
	 */
	public function getHasNext()
	{
		return $this->hasNext;
	}



	public function __construct($counts,$reactions,$hasNext)
	{
		$this->counts = $counts;
		$this->reactions = $reactions;
		$this->hasNext = $hasNext;
	}
}