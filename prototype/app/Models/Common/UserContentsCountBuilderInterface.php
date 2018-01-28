<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/26
 * Time: 12:21
 */

namespace App\Models\Common;


use Illuminate\Database\Eloquent\Builder;

interface UserContentsCountBuilderInterface
{
	/**
	 * @param $userId
	 * @param array $blockUserIds
	 * @param string $blockColumn
	 * @return Builder
	 */
	public function getCountQueryForUser($userId, $blockUserIds = [], $blockColumn = ""):Builder;

}