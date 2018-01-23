<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 2:59
 */

namespace App\Services;


use App\Constants\DateTimeFormat;
use App\Constants\ContributionFeelingType;
use App\Constants\ContributionReactionType;
use App\Constants\FeaturedScheduleType;
use App\Constants\ImageCategory;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Manager\AwsManager;
use App\Models\BlockUser;
use App\Models\DeletedContent;
use App\Models\Contribution;
use App\Models\ContributionAllReaction;
use App\Models\ContributionComment;
use App\Models\ContributionCommentCount;
use App\Models\ContributionHaveReaction;
use App\Models\ContributionInterestReaction;
use App\Models\ContributionLikeReaction;
use App\Models\ContributionReactionCount;
use App\Models\ContributionReactionNotificationDelivery;
use App\Models\FeaturedSchedule;
use App\Models\Follow;
use App\Models\Image;
use App\Models\NegativeProductContribution;
use App\Models\PositiveProductContribution;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryContributionCount;
use App\Models\ProductContributionCount;
use App\Models\ProductsProductCategory;
use App\Models\User;
use App\Services\Tasks\UpdateContributionCountTask;
use App\Services\Tasks\UpdateReactionCountTask;
use App\ValueObject\ContributionDetailResultVO;
use App\ValueObject\ContributionListResultVO;

class ContributionService extends BaseService
{
	/**
	 * @param $userId
	 * @param $productId
	 * @param $contributionFeelingType
	 * @param $content
	 * @param $images
	 * @param int $haveReactionContributionId
	 * @return ServiceResult
	 */
	public function create($userId, $productId, $contributionFeelingType, $content, $images, $haveReactionContributionId ):ServiceResult{
		return $this->executeTasks(function() use ($userId, $productId, $contributionFeelingType, $content, $images, $haveReactionContributionId){
			//重複確認
			if(!empty((new Contribution())->getContributionForUserIdProductId($userId,$productId))){
				return ServiceResult::withError(StatusCode::CONTRIBUTION_ALREADY_EXISTS);
			}

			//Save images
			$imageIds = (new Image())->saveImagesToS3FromFilesGetIds($userId,ImageCategory::CONTRIBUTION,$images);

			//Save contribution
			$contributionId = (new Contribution())->createGetId($userId,$productId,$contributionFeelingType,$content,$imageIds);

			//Update last post date
			(new User())->updateLastPostDate($userId);

			//Increase count.
			$isCreated = true;
			(new UpdateContributionCountTask($contributionId,$productId,$isCreated,$contributionFeelingType))->run();

			//Update have reaction
			if(!empty($haveReactionContributionId)){
				$isIncrease = true;
				var_dump("START HAVE REACTION");
				(new UpdateReactionCountTask($userId,$contributionId,$productId,ContributionReactionType::HAVE,$isIncrease ))->run();
			}

			//return service result with contribution id
			return ServiceResult::withResult($contributionId);

		},true);
	}


	/***
	 * @param $userId
	 * @param $contributionId
	 * @param $content
	 * @return ServiceResult
	 */
	public function edit($userId, $contributionId, $content){
		return $this->executeTasks(function() use ($userId,$contributionId,$content){
			$contributionEntity = (new Contribution())->getContributionForUserIdContributionId($userId,$contributionId);
			if(empty($contributionEntity))
				return ServiceResult::withError(StatusCode::FAILED_TO_FIND_CONTRIBUTION,"contribution id {$contributionId} does not exist on the database.");


			if($userId != $contributionEntity['user_id'])
				return ServiceResult::withError(StatusCode::USER_IS_NOT_OWNER,"User id {$userId} does not match with the contribution user id {$contributionEntity['user_id']}");


			$contributionEntity->content = $content;
			$contributionEntity->save();
			$contributionEntity->modified_at = date(DateTimeFormat::General);
			$result = ['user_id'=>$userId,'review_post_id'=>$contributionId,'message'=>"レビュー投稿の編集が正常に完了しました"];
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
				$contributionEntity = (new Contribution())->getContributionForUserIdProductId($userId,$productId);
				//resultはnullをうけとると自動でエラー処理がはしるのでnullの場合もロジックが走るように配列にする
				$result  = ['entity'=>$contributionEntity];
				return ServiceResult::withResult($result);
			}
		);
	}



	/**
	 * @param $userId
	 * @param $contributionId
	 * @return ServiceResult
	 */
	public function detail($userId, $contributionId):ServiceResult{
		return $this->executeTasks(
			function () use ($userId,$contributionId){
				//Get contribution detail
				$contributionDetail = (new Contribution())->getDetail($userId,$contributionId);
				if($contributionDetail == null)
					return ServiceResult::withError(StatusCode::FAILED_TO_FIND_CONTRIBUTION);

				$product = (new Product())->getProductDetail($contributionDetail->product_id);
				$productCategories = (new ProductsProductCategory())->getProductCategories($contributionDetail->product_id);
				$resultVo = new ContributionDetailResultVO($contributionDetail,$product,$productCategories);

				return ServiceResult::withResult($resultVo);
			}
		);
	}

	/**
	 * @param $userId
	 * @param $contributionId
	 * @return ServiceResult
	 */
	public function delete($userId, $contributionId):ServiceResult{
		return $this->executeTasks(
			function() use ($userId,$contributionId){
				$contributionModel = new Contribution();
				
				//1. check contribution exists and user id is same to contribution owner id
				$contributionEntity = $contributionModel->getContributionForUserIdContributionId($userId,$contributionId);
				if(empty($contributionEntity))
					return ServiceResult::withError(StatusCode::FAILED_TO_FIND_CONTRIBUTION,"contribution id {$contributionId} for the user {$userId} does not exist on the database.");

				//2. Get all comments for saving - max 100
				$commentModel = new ContributionComment();
				$comments = $commentModel->getPureListForContribution($contributionId,100);


				//3. Get reaction  Total  count for saving
				$contributionReactionCountModel = new ContributionReactionCount();
				$contributionReactionCount = $contributionReactionCountModel->getCountsForContribution($contributionId);
				$contributionReactionCountArray = ($contributionReactionCount == null)?[]:$contributionReactionCount->toArray();

				//4. Save data to
				$saveContent = $contributionEntity->toArray();
				$saveRelatedContent = ['comments'=>[],'contribution_count'=>$contributionReactionCountArray];

				if($comments != null) {
					foreach ($comments as $comment) {
						$saveRelatedContent['comments'][] = $comment->toArray();
					}
				}

				$deletedContentId = (new DeletedContent())->createGetId($userId,$contributionModel->getTable(),$contributionId,$saveContent,$saveRelatedContent);

				//Prepare models.
				$productContributionCountModel = (new ProductContributionCount());
				$productCategoryContributionCountModel = (new ProductCategoryContributionCount());
				$productCategoryIds = (new ProductsProductCategory())->getLeafProductCategoryIds($contributionEntity->product_id);
				$allCategoryIds = (new ProductCategory())->getAncestorIdList($productCategoryIds);

				//Decrease counts
				if($contributionEntity->feeling==ContributionFeelingType::POSITIVE){
					$productContributionCountModel->decreasePositiveCount($contributionEntity->product_id);
					$productCategoryContributionCountModel->decreasePositiveCount($allCategoryIds);
				}else {
					$productContributionCountModel->decreaseNegativeCount($contributionEntity->product_id);
					$productCategoryContributionCountModel->decreaseNegativeCount($allCategoryIds);
				}

				//Delete
				$contributionReactionCountModel->deleteAllForContribution($contributionId);
				(new ContributionReactionNotificationDelivery())->deleteAllForContribution($contributionId);
				(new ContributionAllReaction())->deleteAllForContribution($contributionId);
				(new ContributionLikeReaction())->deleteAllForContribution($contributionId);
				(new ContributionCommentCount())->deleteAllForContribution($contributionId);
				(new ContributionInterestReaction())->deleteAllForContribution($contributionId);
				(new ContributionHaveReaction())->deleteAllForContribution($contributionId);
				(new PositiveProductContribution())->deleteAllForContribution($contributionId);
				(new NegativeProductContribution())->deleteAllForContribution($contributionId);


				$commentModel->deleteAllForContribution($contributionId);
				$contributionEntity->delete();
				return ServiceResult::withResult($deletedContentId);
			}
			,true
		);
	}

	/**
	 * @param $userId
	 * @param $productId
	 * @param $type
	 * @param $page
	 * @param $limit
	 * @return ServiceResult
	 */
	public function getListForProduct($userId,$productId,$type,$page,$limit):ServiceResult{
		return $this->executeTasks(function() use ($userId,$productId,$type,$page,$limit){
			$contributions = (new Contribution())->getListForProduct($userId,$productId,$type,$page,$limit);
			$productCategories = (new ProductsProductCategory())->getProductCategories($productId);
			$result = new ContributionListResultVO($contributions,$productCategories);
			return ServiceResult::withResult($result);
		});
	}

	/**
	 * @param $userId
	 * @param $ownerId
	 * @param $page
	 * @param $limit
	 * @return ServiceResult
	 */
	public function getListForOwnerInterest($userId,$ownerId,$page,$limit):ServiceResult{
		return $this->executeTasks(function () use ($userId,$ownerId,$page,$limit){
			$contributions = (new Contribution())->getListForOwnerInterest($userId,$ownerId,$page,$limit);
			$productIds = [];
			foreach($contributions as $contribution){
				$productIds[] = $contribution->product_id;
			}
			$productsCategories = (new ProductsProductCategory())->getProductsCategories($productIds);
			$result = new ContributionListResultVO($contributions,$productsCategories);
			return ServiceResult::withResult($result);
		});

	}

	/**
	 * @param $userId
	 * @param $ownerId
	 * @param $page
	 * @param $limit
	 * @return ServiceResult
	 */
	public function getListForOwner($userId,$ownerId,$page,$limit):ServiceResult{
		return $this->executeTasks(function () use ($userId,$ownerId,$page,$limit){
			$contributions = (new Contribution())->getListForOwner($userId,$ownerId,$page,$limit);
			$productIds = [];
			foreach($contributions as $contribution){
				$productIds[] = $contribution->product_id;
			}
			$productsCategories = (new ProductsProductCategory())->getProductsCategories($productIds);
			$result = new ContributionListResultVO($contributions,$productsCategories);
			return ServiceResult::withResult($result);
		});

	}


	/**
	 * @param $userId
	 * @param $page
	 * @param $limit
	 * @return ServiceResult
	 */
	public function getListForFeed($userId,$page,$limit){
		return $this->executeTasks(function() use($userId,$page,$limit) {
			//Get owner ids for contribution user
			$follows = (new Follow())->getFollowUserIds($userId);
			//Get block list for contribution user
			$blockList = (new BlockUser())->getBlockAndBlockedUserIds($userId);
			//Get Featured users
			$featuredUsers = (new FeaturedSchedule())->getFeaturedUserIds(FeaturedScheduleType::FEED);
			$feedUsers = array_merge($follows, $featuredUsers);
			$feedUsers = array_diff($feedUsers, $blockList);
			$contributions = (new Contribution())->getListForFeed($userId, $feedUsers, $page, $limit);
			$productIds = [];
			foreach ($contributions as $contribution) {
				$productIds[] = $contribution->product_id;
			}
			$productsCategories = (new ProductsProductCategory())->getProductsCategories($productIds);
			$result = new ContributionListResultVO($contributions, $productsCategories);
			return ServiceResult::withResult($result);
		});
	}
}