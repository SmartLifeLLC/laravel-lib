<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 4:35
 */

namespace App\Models;

use DB;
class ProductCategoryFeedCount extends DBModel
{
	protected $guarded = [];

	/**
	 * @param $productCategoryIds
	 */
	public function increasePositiveCount($productCategoryIds){
		$this->updateCount($productCategoryIds,"positive_count","+");
	}

	/**
	 * @param $productCategoryIds
	 */
	public function increaseNegativeCount($productCategoryIds){
		$this->updateCount($productCategoryIds,"negative_count","+");
	}

	/**
	 * @param $productCategoryIds
	 */
	public function decreasePositiveCount($productCategoryIds){
		$this->updateCount($productCategoryIds,"positive_count","-");
	}

	/**
	 * @param $productId
	 */
	public function decreaseNegativeCount($productId){
		$this->updateCount($productId,"negative_count","-");
	}

	/**
	 * @param array $productCategoryIds
	 * @param $column
	 * @param $operator
	 */
	private function updateCount(Array $productCategoryIds,$column,$operator){
		$productCategoryFeedCounts = $this->whereIn('product_category_id',$productCategoryIds)->get();

		//レコードが存在するものとそうでないもの区分
		$updateIds = [];
		foreach ( $productCategoryFeedCounts as $productCategoryFeedCount){
			$updateIds[] = $productCategoryFeedCount->product_category_id;
		}

		var_dump($productCategoryIds);
		var_dump($updateIds);
		$createIds = array_diff($productCategoryIds,$updateIds);

		//run update
		if(count($updateIds) > 0)
			$this->whereIn('product_category_id',$updateIds)->update([$column => DB::raw("{$column} {$operator} 1"),"feed_count"=> DB::raw("feed_count {$operator}  1")]);

		//run create

		if(count($createIds)>0) {
			$createData = [];
			foreach ($createIds as $id) {
				$createData[] = ['product_category_id' => $id, 'feed_count' => 1, $column => 1];
			}
			$this->insert($createData);
		}
	}

}