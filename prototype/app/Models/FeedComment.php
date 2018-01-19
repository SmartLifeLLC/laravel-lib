<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:48
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
use App\Constants\QueryOrderTypes;
use App\Lib\Util;

class FeedComment extends DBModel
{
	/**
	 * @param $userId
	 * @param $feedId
	 * @param $content
	 * @return mixed
	 */
	function createGetId($userId,$feedId,$content){
		return $this->insertGetId(
			[
				'user_id'=>$userId,
				'feed_id'=>$feedId,
				'content'=>$content,
				'created_at'=>date(DateTimeFormat::General),
				'updated_at'=>date(DateTimeFormat::General)

			]
		);
	}

	/**
	 * @param int $feedId
	 * @param int $boundaryId
	 * @param int $limit
	 * @param QueryOrderTypes|null $orderType
	 * @return mixed
	 */
	public function getList(int $feedId, int $boundaryId, int $limit, ?QueryOrderTypes $orderType ) {
		$compareSymbol = $orderType->getQueryCompareSymbol();
		$queryBuilder =
			$this
				->select(
					[
						'feed_comments.id',
						'feed_comments.content',
						'feed_comments.created_at',
						'feed_comments.updated_at',
						'feed_comments.user_id',
						'users.user_name',
						'users.description as introduction',
						'users.gender',
						'users.birthday',
						'users.gender_published_flag',
						'users.birthday_published_flag',
						'profile_image.s3_key as profile_image_s3_key',
						'cover_image.s3_key as cover_image_s3_key',
						'feeds.product_id'
					])
				->where('feed_id',$feedId);
		if($boundaryId > 0){
			$queryBuilder = $queryBuilder->where('id',$compareSymbol,$boundaryId);
		}
		$queryBuilder =
			$queryBuilder
			->leftJoin('feeds','feeds.id','=','feed_comments.feed_id')
			->leftJoin('users','users.id','=','feed_comments.user_id')
			->leftJoin('images as profile_image','profile_image.id','=','users.profile_image_id')
			->leftJoin('images as cover_image','cover_image.id','=','users.cover_image_id');


		$result = $queryBuilder->orderBy('id', $orderType->getValue())->limit($limit)->get();
		if($result === null)
			return [];
		else
			return $result;

	}
}