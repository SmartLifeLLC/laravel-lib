<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 22:13
 */

namespace App\Http\JsonView\Recommend\FeaturedUser;

use App\Http\JsonView\JsonResponseView;

class GetListOnAppInitJsonView extends JsonResponseView
{

    /**
     * @var see FeaturedService::getFeaturedUsersForInitStart
     */
    protected $data;
    function createBody()
    {

    	$result = [];
	    foreach ($this->data as $data){
	    	$user =
			    [
				    "id" => $data['id'],
                    "user_name" => $data['user_name'],
                    "introduction" => $data['description'],
				    "profile_image_url" => $this->getNotNullString($data->profile_image_s3_key),
                    "is_following" => 0
			    ];
			$result[] = $user;
	    }
        $this->body = ['featured_users' => $result];
    }
}