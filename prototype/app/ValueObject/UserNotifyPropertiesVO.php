<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/22
 * Time: 2:57
 */

namespace App\ValueObject;


class UserNotifyPropertiesVO
{
	private $isPermittedFollow;
	private $isPermittedHave;
	private $isPermittedInterest;
	private $isPermittedLike;
	private $isPermittedComment;
	private $data = [];

	/**
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * UserNotifyPropertiesVO constructor.
	 */
	public function __construct()
	{

	}

	/**
	 * @param mixed $isPermittedFollow
	 */
	public function setIsPermittedFollow($isPermittedFollow): void
	{
		if($isPermittedFollow !== null)
			$this->data['is_follow_notification_allowed'] = $isPermittedFollow;

		$this->isPermittedFollow = $isPermittedFollow;
	}

	/**
	 * @param mixed $isPermittedHave
	 */
	public function setIsPermittedHave($isPermittedHave): void
	{
		if($isPermittedHave !== null)
			$this->data['is_have_notification_allowed'] = $isPermittedHave;
		$this->isPermittedHave = $isPermittedHave;
	}

	/**
	 * @param mixed $isPermittedInterest
	 */
	public function setIsPermittedInterest($isPermittedInterest): void
	{
		if($isPermittedInterest !== null)
			$this->data['is_follow_notification_allowed'] = $isPermittedInterest;
		$this->isPermittedInterest = $isPermittedInterest;
	}

	/**
	 * @param mixed $isPermittedLike
	 */
	public function setIsPermittedLike($isPermittedLike): void
	{
		if($isPermittedLike !== null)
			$this->data['is_like_notification_allowed'] = $isPermittedLike;
		$this->isPermittedLike = $isPermittedLike;
	}

	/**
	 * @param mixed $isPermittedComment
	 */
	public function setIsPermittedComment($isPermittedComment): void
	{
		if($isPermittedComment !== null)
			$this->data['is_comment_notification_allowed'] = $isPermittedComment;
		$this->isPermittedComment = $isPermittedComment;
	}



}