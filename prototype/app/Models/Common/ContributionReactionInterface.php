<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:45
 */

namespace App\Models\Common;


interface ContributionReactionInterface
{

	/**
	 * @param $userId
	 * @param $contributionId
	 * @param null $type
	 * @return mixed
	 */
	public function createReaction($userId, $contributionId, $type = null);

	/**
	 * @param $userId
	 * @param $contributionId
	 * @param null $type
	 * @return mixed
	 */
	public function deleteReaction($userId, $contributionId, $type = null);


	/**
	 * @param $userId
	 * @param $contributionId
	 * @param null $type
	 * @return mixed
	 */
	public function findReaction($userId, $contributionId, $type = null);


	/**
	 * @param int $userId
	 * @param int $contributionId
	 * @param array $blockUsers
	 * @param int $page
	 * @param int $limit
	 * @return mixed
	 */
	public function getList(int $userId, int $contributionId, array $blockUsers, int $page, int $limit);

	/**
	 * @return mixed
	 */
	public function getReactionType();

}