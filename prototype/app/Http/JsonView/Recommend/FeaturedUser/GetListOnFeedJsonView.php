<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 22:13
 */

namespace App\Http\JsonView\Recommend\FeaturedUser;

use App\Http\JsonView\JsonResponseView;

class GetListOnFeedJsonView extends JsonResponseView
{

    /**
     * @var see FeaturedService::getFeaturedUsersForFeed
     */
    protected $data;
    function createBody()
    {

    	$result = [];
	    foreach ($this->data as $data){
	    	$user =
			    [
				    "id" => $data->id,
                    "user_name" => $data->user_name,
                    "featured_type" => (rand(0,1)==0)?"おすすめユーザー":"Facebookの友達",
				    "profile_image_url" => "https://cdn.recomil.com/765_user_5/profile/img/origin/2017/11/27/e12efa823e508704405c5b11645af016.jpeg",
                    "is_following" => 0
			    ];
			$result[] = $user;
	    }
        $this->body = ['featured_users' => $result];
    }
}