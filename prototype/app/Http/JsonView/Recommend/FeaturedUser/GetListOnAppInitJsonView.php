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
        $this->body = ['recommend_users' => $this->data];
    }
}