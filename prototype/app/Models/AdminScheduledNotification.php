<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/02/05
 * Time: 23:39
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
use Illuminate\Database\QueryException;
use Mockery\Exception;

class AdminScheduledNotification extends DBModel
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

	/**
	 * @return array
	 */
	public function getNextGlobalNotificationData(){
		$notificationData =
			$this
			->where('scheduled_at','<',date(DateTimeFormat::General))
			->where('is_confirm',1)
			->where('ready_to_send',1)
			->orderBy('scheduled_at','ASC')
			->first();
		if(!empty($notificationData)){
			//Get Execution Authority
			try {
				$this->where('id', $notificationData['id'])->decrement('ready_to_send', 1);
			}catch (QueryException $exception){
				return [];
			}
		}
		return $notificationData;
	}
}