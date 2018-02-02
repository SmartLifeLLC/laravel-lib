<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 22:13
 */

namespace App\Http\JsonView\Recommend\FeaturedUser;

use App\Http\JsonView\JsonResponseView;
use App\ValueObject\GetFeaturedUsersForFeedVO;

class GetListOnFeedTmpJsonView extends JsonResponseView
{


	/**
	 * @var
	 */
    protected $data;
    function createBody()
    {

	    $result = [];
	    for ($i = 0 ; $i < 15 ; $i ++){
	    	$data = $this->data[$i];
	    	$user = $this->getUserHashArray($data->id,$data->user_name,$data->profile_image_s3_key,0,$data->description);
			$user['tp'] = "Facebookの友達";
			$result[] = $user;
	    }
	    for ($i = 15 ; $i < 30 ; $i ++){
		    $data = $this->data[$i];
		    $user = $this->getUserHashArray($data->id,$data->user_name,$data->profile_image_s3_key,0,$data->description);
		    $user['tp'] = "おすすめユーザ";
		    $result[] = $user;
	    }
	    $this->body = ['featured_users' => $result];
    }
}