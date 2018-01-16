<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 2:59
 */

namespace App\Services;


use App\Constants\ImageCategory;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Manager\AwsManager;
use App\Models\Feed;
use App\Models\Image;
use App\Services\Tasks\UpdateFeedCountTask;

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
	public function create($userId, $productId, $feedFeelingType, $content, $images, $haveReactionFeedId = 0):ServiceResult{
		return $this->executeTasks(function() use ($userId, $productId, $feedFeelingType, $content, $images){
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

			//return service result with feed id
			return ServiceResult::withResult($feedId);

		},true);
	}


	public function edit(){

	}

	public function delete(){

	}
}