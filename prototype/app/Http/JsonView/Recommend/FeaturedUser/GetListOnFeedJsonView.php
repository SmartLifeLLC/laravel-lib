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

class GetListOnFeedJsonView extends JsonResponseView
{


	/**
	 * @var GetFeaturedUsersForFeedVO
	 */
    protected $data;
    function createBody()
    {

    	$result = [];

    	$featuredUsersFromFacebook = $this->data->getUsersFromFacebookFriends();
		$featuredUsersFromPickupUsers =  $this->data->getUsersFromPickup();
	    foreach ($featuredUsersFromFacebook as $data){
	    	$user =
			    [
				    "id" => $data->id,
                    "user_name" => $data->user_name,
                    "featured_type" => "Facebookの友達",
				    "profile_image_url" => $this->getImageURLForS3Key($data->profile_image_s3_key),
                    "is_following" => 0
			    ];
			$result[] = $user;
	    }
	    foreach ($featuredUsersFromPickupUsers as $data){
		    $user =
			    [
				    "id" => $data->id,
				    "user_name" => $data->user_name,
				    "featured_type" => "おすすめユーザ",
				    "profile_image_url" => $this->getImageURLForS3Key($data->profile_image_s3_key),
				    "is_following" => 0
			    ];
		    $result[] = $user;
	    }
	    $this->body = ['featured_users' => $result];
    }
}