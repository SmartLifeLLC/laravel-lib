<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 4:37
 */

namespace App\Services\Tasks;


use App\Constants\FeedFeelingType;
use App\Lib\JSYService\ServiceTask;
use App\Models\NegativeProductFeed;
use App\Models\PositiveProductFeed;
use App\Models\ProductCategory;
use App\Models\ProductCategoryFeedCount;
use App\Models\ProductFeedCount;
use App\Models\ProductsProductCategory;

class UpdateFeedCountTask implements ServiceTask
{
	private $feedId;
	private $productId;
	private $feedFeelingType;
	private $isCreated;

	/**
	 * UpdateFeedCountTask constructor.
	 * @param $feedId
	 * @param $productId
	 * @param $isCreated
	 * @param $feedFeelingType
	 */
	public function __construct($feedId,$productId, $isCreated, $feedFeelingType)
	{
		$this->feedId = $feedId;
		$this->productId = $productId ;
		$this->feedFeelingType = $feedFeelingType ;
		$this->isCreated = $isCreated;
	}

	function run()
	{
		($this->isCreated)?$this->createdFeed():$this->deletedFeed();
	}

	private function createdFeed(){
		$allProductCategoryIds = $this->getAllProductCategoryIds();
		if($this->feedFeelingType == FeedFeelingType::POSITIVE)
		{
			(new ProductCategoryFeedCount())->increasePositiveCount($allProductCategoryIds);
			(new ProductFeedCount())->increasePositiveCount($this->productId);
			(new PositiveProductFeed())->createGetId($this->feedId,$this->productId);
		}else {
			(new ProductCategoryFeedCount())->increaseNegativeCount($allProductCategoryIds);
			(new ProductFeedCount())->increaseNegativeCount($this->productId);
			(new NegativeProductFeed())->createGetId($this->feedId,$this->productId);
		};
	}

	private function deletedFeed(){
		$allProductCategoryIds = $this->getAllProductCategoryIds();
		if($this->feedFeelingType == FeedFeelingType::POSITIVE){
			(new ProductCategoryFeedCount())->decreasePositiveCount($allProductCategoryIds);
			(new ProductFeedCount())->decreasePositiveCount($this->productId);
			(new PositiveProductFeed())->remove($this->feedId);
		}else{
			(new ProductCategoryFeedCount())->decreaseNegativeCount($allProductCategoryIds);
			(new ProductFeedCount())->decreaseNegativeCount($this->productId);
			(new NegativeProductFeed())->remove($this->feedId);
		}

	}

	private function getAllProductCategoryIds(){
		$productCategoryDescendantIds = (new ProductsProductCategory())->getProductCategoryIds($this->productId);
		return (new ProductCategory())->getAncestorIdList($productCategoryDescendantIds);
	}

	function getResult()
	{
		return null;
	}
}