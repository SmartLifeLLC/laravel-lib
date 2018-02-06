<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/02/05
 * Time: 23:39
 */

namespace App\Models;


use App\Constants\DateTimeFormat;

class AdminScheduledNotifications extends DBModel
{
	public function getList($limit, $page){
		$offset = $this->getOffset($limit,$page);
		return
			$this
			->where('scheduled_at','<',date(DateTimeFormat::General))
			->where('is_confirm',1)
			->orderBy('scheduled_at','DESC')
			->offset($offset)
			->limit($limit)
			->get();
	}
}