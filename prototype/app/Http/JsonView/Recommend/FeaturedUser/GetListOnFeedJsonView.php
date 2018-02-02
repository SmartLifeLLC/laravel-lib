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
	    	$user = $this->getUserHashArray($data->id,$data->user_name,$data->profile_image_s3_key,0,$data->description);
			$user['tp'] = "Facebookの友達";
			$result[] = $user;
	    }
	    foreach ($featuredUsersFromPickupUsers as $data){
		    $user = $this->getUserHashArray($data->id,$data->user_name,$data->profile_image_s3_key,0,$data->description);
			$user['tp'] = "おすすめユーザ";
		    $result[] = $user;
	    }
	    $this->body = ['featured_users' => $result];
    }
}