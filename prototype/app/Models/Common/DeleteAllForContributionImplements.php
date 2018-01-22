<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 18:19
 */

namespace App\Models\Common;


trait DeleteAllForContributionImplements
{
	/**
	 * @param $contributionId
	 * @return mixed
	 */
	public function deleteAllForContribution($contributionId){
		return $this->where('contribution_id',$contributionId)->delete();
	}
}
