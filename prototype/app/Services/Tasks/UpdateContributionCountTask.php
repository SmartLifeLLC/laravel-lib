<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 4:37
 */

namespace App\Services\Tasks;


use App\Constants\ContributionFeelingType;
use App\Lib\JSYService\ServiceTask;
use App\Models\NegativeProductContribution;
use App\Models\PositiveProductContribution;
use App\Models\ProductCategory;
use App\Models\ProductCategoryContributionCount;
use App\Models\ProductContributionCount;
use App\Models\ProductsProductCategory;

class UpdateContributionCountTask implements ServiceTask
{
	private $contributionId;
	private $productId;
	private $contributionFeelingType;
	private $isCreated;

	/**
	 * UpdateContributionCountTask constructor.
	 * @param $contributionId
	 * @param $productId
	 * @param $isCreated
	 * @param $contributionFeelingType
	 */
	public function __construct($contributionId, $productId, $isCreated, $contributionFeelingType)
	{
		$this->contributionId = $contributionId;
		$this->productId = $productId ;
		$this->contributionFeelingType = $contributionFeelingType ;
		$this->isCreated = $isCreated;
	}

	function run()
	{
		($this->isCreated)?$this->createdContribution():$this->deletedContribution();
	}

	private function createdContribution(){
		$allProductCategoryIds = $this->getAllProductCategoryIds();
		if($this->contributionFeelingType == ContributionFeelingType::POSITIVE)
		{
			(new ProductCategoryContributionCount())->increasePositiveCount($allProductCategoryIds);
			(new ProductContributionCount())->increasePositiveCount($this->productId);
			(new PositiveProductContribution())->createGetId($this->contributionId,$this->productId);
		}else {
			(new ProductCategoryContributionCount())->increaseNegativeCount($allProductCategoryIds);
			(new ProductContributionCount())->increaseNegativeCount($this->productId);
			(new NegativeProductContribution())->createGetId($this->contributionId,$this->productId);
		};
	}

	private function deletedContribution(){
		$allProductCategoryIds = $this->getAllProductCategoryIds();
		if($this->contributionFeelingType == ContributionFeelingType::POSITIVE){
			(new ProductCategoryContributionCount())->decreasePositiveCount($allProductCategoryIds);
			(new ProductContributionCount())->decreasePositiveCount($this->productId);
			(new PositiveProductContribution())->remove($this->contributionId);
		}else{
			(new ProductCategoryContributionCount())->decreaseNegativeCount($allProductCategoryIds);
			(new ProductContributionCount())->decreaseNegativeCount($this->productId);
			(new NegativeProductContribution())->remove($this->contributionId);
		}

	}

	private function getAllProductCategoryIds(){
		$productCategoryDescendantIds = (new ProductsProductCategory())->getLeafProductCategoryIds($this->productId);
		return (new ProductCategory())->getAncestorIdList($productCategoryDescendantIds);
	}

	function getResult()
	{
		return null;
	}
}