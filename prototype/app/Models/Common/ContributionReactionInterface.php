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
	 * @param $userId
	 * @param $contributionId
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getList($userId, $contributionId, $page, $limit);

	/**
	 * @return mixed
	 */
	public function getReactionType();

}