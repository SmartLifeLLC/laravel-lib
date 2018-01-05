<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 20:38
 */
namespace App\Http\Controllers\Featured;
use App\Models\CurrentUser;
use App\Http\Controllers\Controller;
use App\Http\JsonView\Recommend\FeaturedUser\GetListOnAppInitJsonView;
use App\Services\FeaturedService;

class FeaturedUsersController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListOnAppInit(){
        $userId = CurrentUser::shared()->getUserId();
        $serviceResult = (new FeaturedService())->getFeaturedUsersForInitStart($userId);
        $jsonView = new GetListOnAppInitJsonView($serviceResult);
        return $this->responseJson($jsonView);
    }

}