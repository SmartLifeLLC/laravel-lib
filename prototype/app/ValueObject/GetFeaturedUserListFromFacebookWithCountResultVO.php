<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/02/02
 * Time: 3:37
 */

namespace App\ValueObject;


class GetFeaturedUserListFromFacebookWithCountResultVO
{

	private $data;
	private $hasNext;
	private $count;
	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return bool
	 */
	public function isHasNext(): bool
	{
		return $this->hasNext;
	}

	/**
	 * @return int
	 */
	public function getCount(): int
	{
		return $this->count;
	}



	public function __construct($data,bool $hasNext, int $count)
	{
		$this->data = $data;
		$this->hasNext = $hasNext;
		$this->count = $count;
	}
}