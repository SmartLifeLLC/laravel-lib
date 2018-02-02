<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 22:13
 */

namespace App\Http\JsonView\Recommend\FeaturedUser;

use App\Http\JsonView\JsonResponseView;
use App\ValueObject\GetFeaturedUserListFromFacebookWithCountResultVO;
use App\ValueObject\GetFeaturedUsersForFeedVO;

class GetListOnFacebookJsonView extends JsonResponseView
{


	/**
	 * @var GetFeaturedUserListFromFacebookWithCountResultVO
	 */
    protected $data;
    function createBody()
    {

    	$result = [];
    	$featuredUsersFromFacebook = $this->data->getData();
		$hasNext = $this->data->isHasNext();
	    foreach ($featuredUsersFromFacebook as $data){
	    	$user =

			    $this->getUserHashArray(
			    	$data->id,
				    $data->user_name,
				    $data->profile_image_s3_key,
				    0,
				    $data->description
			    );

			$result[] = $user;
	    }
	    $this->body = ['featured_users' => $result,'has_next' => $this->getBinaryValue($hasNext)];
    }
}