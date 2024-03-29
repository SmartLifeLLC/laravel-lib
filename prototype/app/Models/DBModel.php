<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/15
 * Time: 22:07
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class DBModel extends Model
{
    public $timestamps = false;

    /**
     * @param $limit
     * @param $page
     * @return int
     */
    protected function getOffset($limit,$page):int{
        if($page < 1) $page = 1;
        return ($page - 1) * $limit;
    }

	/**
	 * @param $limit
	 * @param $page
	 * @param $totalCount
	 * @return bool
	 */
    public function getHasNext($limit,$page,$totalCount):bool{
    	$offset = $this->getOffset($limit,$page);
    	$current = $offset + $limit;
	    return ($current < $totalCount)?true:false;
    }

	/**
	 * @param $hashData
	 * @param $key
	 * @return array
	 */
    public function getArrayWithoutKey($hashData,$key):array {
	    //Make id array
	    $arrayData = array_map(function($item) use ($key) {
	    	if(is_array($item))
		        return  $item[$key];
	    	else {
			    return $item->{$key};
		    }
	    }, $hashData);
	    return $arrayData;
    }
}