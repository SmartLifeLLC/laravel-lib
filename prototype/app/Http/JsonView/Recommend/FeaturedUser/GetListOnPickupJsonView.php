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

class GetListOnPickupJsonView extends JsonResponseView
{


	/**
	 * @var GetFeaturedUsersForFeedVO
	 */
    protected $data;
    function createBody()
    {

	    $result = [];
	    foreach ($this->data as $data){
		    $result[] =
			    $this->getUserHashArray(
				    $data->id,
				    $data->user_name,
				    $data->profile_image_s3_key,
				    0,
				    $data->description
			    );
	    }
	    $this->body = ['featured_users' => $result];
    }
}