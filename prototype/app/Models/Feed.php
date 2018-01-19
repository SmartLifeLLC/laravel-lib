<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 4:05
 */

namespace App\Models;


class Feed extends DBModel
{
	protected $guarded = [];

	/**
	 * @param $userId
	 * @param $productId
	 * @param $feedFeelingType
	 * @param $content
	 * @param $imageIds
	 * @return mixed
	 */
	public function createGetId($userId,$productId,$feedFeelingType,$content,$imageIds){
		$data = ['user_id'=>$userId,'product_id'=>$productId,'feeling'=>$feedFeelingType,'content'=>$content];
		for($i = 0 ; $i < count($imageIds) ; $i ++){
			$data['image_id_'.$i] = $imageIds[$i];
		}
		return $this->insertGetId($data);
	}

	/**
	 * @param $userId
	 * @param $productId
	 * @return mixed
	 */
	public function getFeedForUserIdProductId($userId,$productId){
		return $this->where('user_id',$userId)->where('product_id',$productId)->first();
	}

	/**
	 * @param $userId
	 * @return int
	 */
	public function getCountForUser($userId):int{
		return $this->where('user_id',$userId)->count();
	}

}