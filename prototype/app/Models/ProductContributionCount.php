<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 4:35
 */

namespace App\Models;


class ProductContributionCount extends DBModel
{
	protected $guarded = [];

	/**
	 * @param $productId
	 */
	public function increasePositiveCount($productId){
		$this->updateCount($productId,"positive_count","+");
	}

	/**
	 * @param $productId
	 */
	public function increaseNegativeCount($productId){
		$this->updateCount($productId,"negative_count","+");
	}

	/**
	 * @param $productId
	 */
	public function decreasePositiveCount($productId){
		$this->updateCount($productId,"positive_count","-");
	}

	/**
	 * @param $productId
	 */
	public function decreaseNegativeCount($productId){
		$this->updateCount($productId,"negative_count","-");
	}

	/**
	 * @param $productId
	 * @param $feelingColumn
	 * @param $operator
	 */
	private function updateCount($productId,$feelingColumn,$operator){
		$productContributionCount = $this->where('product_id',$productId)->first();
		if($productContributionCount == null){
			$this->insert(['product_id'=>$productId,$feelingColumn=>1,'contribution_count'=>1]);
		}else{
			$amount = ($operator == "+")?1:-1;
			$productContributionCount->{$feelingColumn} += $amount;
			$productContributionCount->contribution_count += $amount ;
			$productContributionCount->save();
		}
	}
}