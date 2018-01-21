<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 2:59
 */

namespace App\Services;


use App\Constants\DateTimeFormat;
use App\Constants\FeedFeelingType;
use App\Constants\FeedReactionType;
use App\Constants\ImageCategory;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Manager\AwsManager;
use App\Models\DeletedContent;
use App\Models\Feed;
use App\Models\FeedAllReaction;
use App\Models\FeedComment;
use App\Models\FeedHaveReaction;
use App\Models\FeedInterestReaction;
use App\Models\FeedLikeReaction;
use App\Models\FeedReactionCount;
use App\Models\FeedReactionNotificationDelivery;
use App\Models\Image;
use App\Models\NegativeProductFeed;
use App\Models\PositiveProductFeed;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryFeedCount;
use App\Models\ProductFeedCount;
use App\Models\ProductsProductCategory;
use App\Services\Tasks\UpdateFeedCountTask;
use App\Services\Tasks\UpdateReactionCountTask;
use App\ValueObject\ContributionGetDetailResultVO;

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

			//Update last post date
			(new User())->

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
			$feedEntity = (new Feed())->getFeedForUserIdFeedId($userId,$feedId);
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



	/**
	 * @param $userId
	 * @param $feedId
	 * @return ServiceResult
	 */
	public function detail($userId,$feedId):ServiceResult{
		return $this->executeTasks(
			function () use ($userId,$feedId){
				//Get Feed Detail
				$feedDetail = (new Feed())->getDetail($userId,$feedId);
				$product = (new Product())->getProductDetail($feedDetail->product_id);
				$productCategories = (new ProductsProductCategory())->getProductCategories($feedDetail->product_id);
				$commentCount =(new FeedComment())->getCountForFeed($feedId);
				$resultVo = new ContributionGetDetailResultVO($feedDetail,$product,$productCategories,$commentCount);

				return ServiceResult::withResult($resultVo);
			}
		);
	}

	/**
	 * @param $userId
	 * @param $feedId
	 * @return ServiceResult
	 */
	public function delete($userId,$feedId):ServiceResult{
		return $this->executeTasks(
			function() use ($userId,$feedId){
				$feedModel = new Feed();
				
				//1. check feed exists and user id is same to feed owner id
				$feedEntity = $feedModel->getFeedForUserIdFeedId($userId,$feedId);
				if(empty($feedEntity))
					return ServiceResult::withError(StatusCode::FAILED_TO_FIND_FEED,"Feed id {$feedId} for the user {$userId} does not exist on the database.");

				//2. Get all comments for saving - max 100
				$commentModel = new FeedComment();
				$comments = $commentModel->getPureListForFeed($feedId,100);


				//3. Get reaction  Total  count for saving
				$feedReactionCountModel = new FeedReactionCount();
				$feedReactionCount = $feedReactionCountModel->getCountsForFeed($feedId);
				$feedReactionCountArray = ($feedReactionCount == null)?[]:$feedReactionCount->toArray();

				//4. Save data to
				$saveContent = $feedEntity->toArray();
				$saveRelatedContent = ['comments'=>[],'feed_count'=>$feedReactionCountArray];

				if($comments != null) {
					foreach ($comments as $comment) {
						$saveRelatedContent['comments'][] = $comment->toArray();
					}
				}

				$deletedContentId = (new DeletedContent())->createGetId($userId,$feedModel->getTable(),$feedId,$saveContent,$saveRelatedContent);

				//Prepare models.
				$productFeedCountModel = (new ProductFeedCount());
				$productCategoryFeedCountModel = (new ProductCategoryFeedCount());
				$productCategoryIds = (new ProductsProductCategory())->getLeafProductCategoryIds($feedEntity->product_id);
				$allCategoryIds = (new ProductCategory())->getAncestorIdList($productCategoryIds);

				//Decrease counts
				if($feedEntity->feeling==FeedFeelingType::POSITIVE){
					$productFeedCountModel->decreasePositiveCount($feedEntity->product_id);
					$productCategoryFeedCountModel->decreasePositiveCount($allCategoryIds);
				}else {
					$productFeedCountModel->decreaseNegativeCount($feedEntity->product_id);
					$productCategoryFeedCountModel->decreaseNegativeCount($allCategoryIds);
				}

				//Delete
				$feedReactionCountModel->deleteAllForFeed($feedId);
				(new FeedReactionNotificationDelivery())->deleteAllForFeed($feedId);
				(new FeedAllReaction())->deleteAllForFeed($feedId);
				(new FeedLikeReaction())->deleteAllForFeed($feedId);
				(new FeedInterestReaction())->deleteAllForFeed($feedId);
				(new FeedHaveReaction())->deleteAllForFeed($feedId);
				(new PositiveProductFeed())->deleteAllForFeed($feedId);
				(new NegativeProductFeed())->deleteAllForFeed($feedId);
				$commentModel->deleteAllForFeed($feedId);
				$feedEntity->delete();
				return ServiceResult::withResult($deletedContentId);
			}
			,true
		);
	}
}