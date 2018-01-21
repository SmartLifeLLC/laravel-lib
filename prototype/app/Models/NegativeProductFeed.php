<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 4:34
 */

namespace App\Models;


use App\Models\Common\DeleteAllForFeedImplements;
use App\Models\Common\DeleteAllForFeedInterface;

class NegativeProductFeed extends DBModel implements DeleteAllForFeedInterface
{

	use DeleteAllForFeedImplements;
	/**
	 * @param $feedId
	 * @param $productId
	 * @return mixed
	 */
	public function createGetId($feedId,$productId){
		return $this->insertGetId(['feed_id'=>$feedId,'product_id'=>$productId]);
	}

	/**
	 * @param $feedId
	 */
	public function remove($feedId){
		$data = $this->where(['feed_id'=>$feedId])->first();
		if(!empty($data)){
			$this->destroy($data->id);
		}
	}
}