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
use App\Services\Tasks\NotificationTask\Factory\CommentNotificationFactory;
use App\Services\Tasks\NotificationTask\Factory\CommentRelatedNotificationFactory;
use App\Services\Tasks\SendNotificationTask;
use MongoDB\Driver\Query;

class CommentService extends BaseService
{
	/**
	 * @param $userId
	 * @param $contributionId
	 * @param $content
	 * @return ServiceResult
	 */
	public function create($userId, $contributionId, $content):ServiceResult{
		return $this->executeTasks(function()use($userId,$contributionId,$content){

			$blockModel = new BlockUser();
			$contribution = (new Contribution())->find($contributionId);
			$contributionUserId = $contribution['user_id'];
			if($blockModel->isBlockStatus($userId,$contributionUserId)){
				return ServiceResult::withBlockStatusError($userId,$contributionUserId);
			}


			//Create comment Data
			$contributionCommentModel = new ContributionComment();
			$commentId = $contributionCommentModel->createGetId($userId,$contributionId,$content);
			(new ContributionCommentCount())->increaseCommentCount($contributionId);


			//Send notification to comment owner
			$user = (new User())->getSimpleUserInfo($userId);
			$notificationLogModel = new NotificationLog();
			$notificationTargetUsers = (new Device())->getNotificationTargetUsers([$contribution['user_id']]);
			$factory = new CommentNotificationFactory();
			$notification =
				$factory
					->setProductName($contribution['display_name'])
					->setUserName($user['user_name'])
					->setFromUserId($userId)
					->setTargetUsers($notificationTargetUsers)
					->create();
			//insert
			$notificationLogModel->saveData($notification->saveData());
			//Send
			$notification->send();


			//Send notification to comment related users
			$relatedNotificationTargetUsersDirty = $contributionCommentModel->getNotificationTargetUsersDirty($userId,$contributionId);
			if(!empty($relatedNotificationTargetUsers)){
				//Send every 1000 data
				$count = 0;
				$notificationTargetUsers = [];
				foreach($relatedNotificationTargetUsersDirty as $user){

					//block 状態を確認
					if(empty($user['block']) && empty($user['blocked'])){
						if( !isset($notificationTargetUsers[$user['user_id']])){
							$notificationTargetUsers[$user['user_id']] = [];
						}
						$notificationTargetUsers[$user['user_id']][] = $notificationTargetUsers['notification_token'];
						$count++;
						if($count > 1000){
							$relatedFactory = new CommentRelatedNotificationFactory();
							$notification =
								$relatedFactory
								->setProductName($contribution['display_name'])
								->setUserName($user['user_name'])
								->setFromUserId($userId)
								->setTargetUsers($notificationTargetUsers)
								->create();
							$notificationLogModel->saveData($notification->saveData());
							$notification->send();
							$notificationTargetUsers = [];
						}
					}
				}

				//Send remained one.　
				if(!empty($notificationTargetUsers)){
					$relatedFactory = new CommentRelatedNotificationFactory();
					$notification =
						$relatedFactory
							->setProductName($contribution['display_name'])
							->setUserName($user['user_name'])
							->setFromUserId($userId)
							->setTargetUsers($notificationTargetUsers)
							->create();
					$notificationLogModel->saveData($notification->saveData());
					$notification->send();
				}
			}
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