<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:46
 */

namespace App\Services;


use App\Constants\QueryOrderTypes;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Models\Feed;
use App\Models\FeedComment;
use App\Services\Tasks\SendNotificationTask;
use MongoDB\Driver\Query;

class CommentService extends BaseService
{
	/**
	 * @param $userId
	 * @param $feedId
	 * @param $content
	 * @return ServiceResult
	 */
	public function create($userId,$feedId,$content):ServiceResult{
		return $this->executeTasks(function()use($userId,$feedId,$content){
			//Create comment Data
			$commentId = (new FeedComment())->createGetId($userId,$feedId,$content);
			//Send notification
			$feed = (new Feed())->find($feedId);
			(new SendNotificationTask())->sendCommentNotification($userId,$feed['user_id'],$commentId,$content);
			return ServiceResult::withResult($commentId);
		},true);
	}


	/**
	 * @param $userId
	 * @param $commentId
	 * @return ServiceResult
	 */
	public function delete($userId,$commentId):ServiceResult{
		return $this->executeTasks(function()use($userId,$commentId){
			//Check user is comment owner
			$commentEntity = (new FeedComment())->find($commentId);
			if(empty($commentEntity))
				return ServiceResult::withError(StatusCode::FAILED_TO_FIND_FEED_COMMENT);

			if($commentEntity['user_id'] != $userId )
				return ServiceResult::withError(StatusCode::USER_IS_NOT_OWNER,"user id {$userId} does not matched with comment owner id {$commentEntity['user_id']}");

			//todo -> move deleted data to deleted contents.
			$commentEntity->delete();
			$result = ['user_id'=>$userId,'comment_id'=>$commentId];
			return ServiceResult::withResult($result);

		},true);
	}

	/**
	 * @param $feedId
	 * @param $boundaryId
	 * @param $isAsc
	 * @param $limit
	 * @return ServiceResult
	 */
	public function getList($feedId,$boundaryId,$isAsc,$limit){
		return $this->executeTasks(function() use ($feedId,$boundaryId,$isAsc,$limit){
			$type = ($isAsc)?QueryOrderTypes::ASCENDING:QueryOrderTypes::DESCENDING;
			$queryOrderType = new QueryOrderTypes($type);
			$commentList = (new FeedComment())->getList($feedId,$boundaryId,$limit,$queryOrderType);
			return ServiceResult::withResult($commentList);
		});
	}
}