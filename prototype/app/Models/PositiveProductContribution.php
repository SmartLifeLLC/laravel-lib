<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 4:34
 */

namespace App\Models;


use App\Models\Common\DeleteAllForContributionImplements;
use App\Models\Common\DeleteAllForContributionInterface;

class PositiveProductContribution extends DBModel implements DeleteAllForContributionInterface
{
	use DeleteAllForContributionImplements;
	/**
	 * @param $contributionId
	 * @param $productId
	 * @return mixed
	 */
	public function createGetId($contributionId, $productId){
		return $this->insertGetId(['contribution_id'=>$contributionId,'product_id'=>$productId]);
	}

	/**
	 * @param $contributionId
	 */
	public function remove($contributionId){
		$data = $this->where(['contribution_id'=>$contributionId])->first();
		if(!empty($data)){
			$this->destroy($data->id);
		}
	}
}