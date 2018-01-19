<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 2:59
 */

namespace App\Services;


use App\Constants\DateTimeFormat;
use App\Constants\FeedReactionType;
use App\Constants\ImageCategory;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Manager\AwsManager;
use App\Models\Feed;
use App\Models\Image;
use App\Services\Tasks\UpdateFeedCountTask;
use App\Services\Tasks\UpdateReactionCountTask;

class ContributionService extends BaseService
{
	/**
	 * @param $userId
	 * @param $productId
	 * @param $feedFeelingType
	 * @param $content
	 * @param $images
	 * @param int $haveReactionFeedId
	 * @return ServiceResult
	 */
	public function create($userId, $productId, $feedFeelingType, $content, $images, $haveReactionFeedId ):ServiceResult{
		return $this->executeTasks(function() use ($userId, $productId, $feedFeelingType, $content, $images, $haveReactionFeedId){
			//重複確認
			if(!empty((new Feed())->getFeedForUserIdProductId($userId,$productId))){
				return ServiceResult::withError(StatusCode::CONTRIBUTION_ALREADY_EXISTS);
			}

			//Save images
			$imageIds = (new Image())->saveImagesToS3FromFilesGetIds($userId,ImageCategory::FEED,$images);

			//Save feed
			$feedId = (new Feed())->createGetId($userId,$productId,$feedFeelingType,$content,$imageIds);

			//Increase count.
			$isCreated = true;
			(new UpdateFeedCountTask($feedId,$productId,$isCreated,$feedFeelingType))->run();

			//Update have reaction
			if(!empty($haveReactionFeedId)){
				$isIncrease = true;
				var_dump("START HAVE REACTION");
				(new UpdateReactionCountTask($userId,$feedId,$productId,FeedReactionType::HAVE,$isIncrease ))->run();
			}

			//return service result with feed id
			return ServiceResult::withResult($feedId);

		},true);
	}


	/***
	 * @param $userId
	 * @param $feedId
	 * @param $content
	 * @return ServiceResult
	 */
	public function edit($userId,$feedId,$content){
		return $this->executeTasks(function() use ($userId,$feedId,$content){
			$feedEntity = (new Feed())->find($feedId);
			if(empty($feedEntity))
				return ServiceResult::withError(StatusCode::FAILED_TO_FIND_FEED,"Feed id {$feedId} does not exist on the database.");


			if($userId != $feedEntity['user_id'])
				return ServiceResult::withError(StatusCode::USER_IS_NOT_OWNER,"User id {$userId} does not match with the feed user id {$feedEntity['user_id']}");


			$feedEntity->content = $content;
			$feedEntity->save();
			$feedEntity->modified_at = date(DateTimeFormat::General);
			$result = ['user_id'=>$userId,'review_post_id'=>$feedId,'message'=>"レビュー投稿の編集が正常に完了しました"];
			return ServiceResult::withResult($result);

		},true);
	}

	/**
	 * @param $userId
	 * @param $productId
	 * @return ServiceResult
	 */
	public function find($userId,$productId){
		return $this->executeTasks(
			function() use ($userId,$productId){
				$feedEntity = (new Feed())->getFeedForUserIdProductId($userId,$productId);
				//resultはnullをうけとると自動でエラー処理がはしるのでnullの場合もロジックが走るように配列にする
				$result  = ['entity'=>$feedEntity];
				return ServiceResult::withResult($result);
			}
		);
	}

	public function delete(){

	}
}