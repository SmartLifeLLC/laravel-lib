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

	    foreach ($this->data as &$data){
		    $data->profile_image_url = $this->getNotNullString($data->profile_image_s3_key);
		    $data->is_following = false;
		    $data->is_followered = false;
	    }
        $this->body = ['recommend_users' => $this->data];
    }
}