<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 7:45
 */

namespace App\Models\Common;


interface FeedReactionInterface
{

	/**
	 * @param $userId
	 * @param $feedId
	 * @param null $type
	 * @return mixed
	 */
	public function createReaction($userId,$feedId,$type = null);

	/**
	 * @param $userId
	 * @param $feedId
	 * @param null $type
	 * @return mixed
	 */
	public function deleteReaction($userId,$feedId,$type = null);

	/**
	 * @param $userId
	 * @param $feedId
	 * @param null $type
	 * @return mixed
	 */
	public function findReaction($userId,$feedId,$type = null);

	/**
	 * @param $userId
	 * @param $feedId
	 * @param $page
	 * @param $limit
	 * @return mixed
	 */
	public function getList($userId,$feedId,$page,$limit);

	/**
	 * @return mixed
	 */
	public function getReactionType();

}