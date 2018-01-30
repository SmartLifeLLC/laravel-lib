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
use App\Models\BlockUser;
use App\Models\Contribution;
use App\Models\ContributionComment;
use App\Models\ContributionCommentCount;
use App\Models\ContributionReactionCount;
use App\Models\Device;
use App\Models\NotificationLog;
use App\Models\User;
use App\Factory\CommentNotificationFactory;
use App\Factory\CommentRelatedNotificationFactory;
use App\Services\Tasks\SendNotificationTask;
use MongoDB\Driver\Query;

class CommentService extends BaseService
{
	/**
	 * @param $commentUserId
	 * @param $contributionId
	 * @param $content
	 * @return ServiceResult
	 */
	public function create($commentUserId, $contributionId, $content):ServiceResult{
		return $this->executeTasks(function()use($commentUserId,$contributionId,$content){

			$blockModel = new BlockUser();
			$contribution = (new Contribution())->getContributionWithProductName($contributionId);
			$contributionUserId = $contribution['user_id'];
			if($blockModel->isBlockStatus($commentUserId,$contributionUserId)){
				return ServiceResult::withBlockStatusError($commentUserId,$contributionUserId);
			}


			//Create comment Data
			$contributionCommentModel = new ContributionComment();
			$commentId = $contributionCommentModel->createGetId($commentUserId,$contributionId,$content);
			(new ContributionCommentCount())->increaseCommentCount($contributionId);


			$userModel = new User();
			//Check comment owner setting and get owner notification tokens
			$notificationCheckColumn = CommentRelatedNotificationFactory::getNotificationAllowColumn();
			$commentUser = $userModel->getSimpleUserInfo($commentUserId);
			$notificationLogModel = new NotificationLog();
			$notificationTargetUserList = (new Device())->getNotificationTargetUsers([$contribution['user_id']],$notificationCheckColumn);
			//If notification token does not empty the owner also allowed receive notification.
			if(!empty($notificationTargetUserList)) {
				$factory = new CommentNotificationFactory();
				$notification =
					$factory
						->setProductName($contribution['display_name'])
						->setUserName($commentUser['user_name'])
						->setContributionId($contributionId)
						->setContributionCommentId($commentId)
						->setFromUserId($commentUserId)
						->setTargetUsers($notificationTargetUserList)
						->create();
				//make log
				$notificationLogModel->saveData($notification->getSaveData());
				//Send
				$notification->run();
			}

			//Get user list for same comment.
			$relatedNotificationTargetUsersDirty = $contributionCommentModel->getNotificationTargetUsersDirty($commentUserId,$contributionId,$notificationCheckColumn);
			//var_dump($relatedNotificationTargetUsersDirty);
			//Send notification to comment related users
			if(($relatedNotificationTargetUsersDirty->count()) > 0){
				//Send every 1000 data
				$count = 0;
				$notificationTargetUserList = [];
				foreach($relatedNotificationTargetUsersDirty as $notificationTargetUser){
					//var_dump($notificationTargetUser->toArray());
					//block 状態を確認 / 自分自身には送らない
					if(empty($notificationTargetUser['block']) && empty($notificationTargetUser['blocked']) && $notificationTargetUser['user_id'] != $commentUserId)
					{
						//配信用の配列を準備 user ->[token1,token2,token3] このトークンリストをFirebaseへ転送
						if(!isset($notificationTargetUserList[$notificationTargetUser['user_id']])){
							$notificationTargetUserList[$notificationTargetUser['user_id']] = [];
						}

						$notificationTargetUserList[$notificationTargetUser['user_id']][]
							= $notificationTargetUser['notification_token'];

						$count++;
						if($count >= 1000){
							$this->notificationSaveAndSend($notificationTargetUserList,$contribution,$commentUser,$notificationLogModel,$commentId);
							$notificationTargetUserList = [];
						}
					}
				}

				//Send remained one.　
				if(!empty($notificationTargetUserList)){
					$this->notificationSaveAndSend($notificationTargetUserList,$contribution,$commentUser,$notificationLogModel,$commentId);
				}
			}
			return ServiceResult::withResult($commentId);
		},true);
	}


	/**
	 * @param array $notificationTargetUserList
	 * @param $contribution
	 * @param $commentUser
	 * @param $notificationLogModel
	 * @param $commentId
	 */
	private function notificationSaveAndSend(array $notificationTargetUserList,$contribution,$commentUser,NotificationLog $notificationLogModel,$commentId){
		$relatedFactory = new CommentRelatedNotificationFactory();
		$notification =
			$relatedFactory
				->setProductName($contribution['display_name'])
				->setUserName($commentUser['user_name'])
				->setFromUserId($commentUser['id'])
				->setTargetUsers($notificationTargetUserList)
				->setContributionId($contribution['id'])
				->setContributionCommentId($commentId)
				->create();
		$notificationLogModel->saveData($notification->getSaveData());
		$notification->run();
	}

	/**
	 * @param $userId
	 * @param $commentId
	 * @return ServiceResult
	 */
	public function delete($userId,$commentId):ServiceResult{
		return $this->executeTasks(function()use($userId,$commentId){
			//Check user is comment owner
			$commentEntity = (new ContributionComment())->find($commentId);
			if(empty($commentEntity))
				return ServiceResult::withError(StatusCode::FAILED_TO_FIND_CONTRIBUTION_COMMENT);

			if($commentEntity['user_id'] != $userId )
				return ServiceResult::withError(StatusCode::USER_IS_NOT_OWNER,"user id {$userId} does not matched with comment owner id {$commentEntity['user_id']}");

			(new ContributionCommentCount())->decreaseCommentCount($commentEntity->contribution_id);

			//todo -> move deleted data to deleted contents.
			$commentEntity->delete();
			$result = ['user_id'=>$userId,'comment_id'=>$commentId];
			return ServiceResult::withResult($result);

		},true);
	}

	/**
	 * @param $userId
	 * @param $contributionId
	 * @param $boundaryId
	 * @param $isAsc
	 * @param $limit
	 * @return ServiceResult
	 */
	public function getList($userId, $contributionId, $boundaryId, $isAsc, $limit){
		return $this->executeTasks(function() use ($userId, $contributionId,$boundaryId,$isAsc,$limit){
			$blockList =(new BlockUser())->getBlockAndBlockedUserIds($userId);
			$type = ($isAsc)?QueryOrderTypes::ASCENDING:QueryOrderTypes::DESCENDING;
			$queryOrderType = new QueryOrderTypes($type);
			$commentList = (new ContributionComment())->getList($contributionId,$boundaryId,$blockList,$limit,$queryOrderType);
			return ServiceResult::withResult($commentList);
		});
	}
}