<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 18:19
 */

namespace App\Models\Common;


use Illuminate\Database\Eloquent\Builder;

trait UserContentsCountBuilderImplements
{
	/**
	 * @param $userId
	 * @param array $blockUserIds
	 * @param string $blockColumn
	 * @return Builder
	 */
	public function getCountQueryForUser($userId, $blockUserIds = [], $blockColumn = "target_user_id"):Builder{
		$tableName = $this->getTable();
		if(empty($blockUserIds))
			return self::where("{$tableName}.user_id",$userId);

		//Todo : Change follow query with Not exists.
		else
			return  self::where("{$tableName}.user_id",$userId)->whereNotIn($blockColumn,$blockUserIds);

	}
}
