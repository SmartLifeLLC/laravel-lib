<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 20:38
 */
namespace App\Http\Controllers\Featured;
use App\Constants\DefaultValues;
use App\Http\JsonView\Recommend\FeaturedUser\GetListOnFacebookJsonView;
use App\Http\JsonView\Recommend\FeaturedUser\GetListOnFeedJsonView;
use App\Http\JsonView\Recommend\FeaturedUser\GetListOnFeedTmpJsonView;
use App\Http\JsonView\Recommend\FeaturedUser\GetListOnPickupJsonView;
use App\Lib\Util;
use App\Models\CurrentUser;
use App\Http\Controllers\Controller;
use App\Http\JsonView\Recommend\FeaturedUser\GetListOnAppInitJsonView;
use App\Services\FeaturedService;
use Illuminate\Http\Request;

class FeaturedUsersController extends Controller
{
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function getList(Request $request){


	    $type = $request->get('type','init');
	    $limit = Util::getValueForKeyOnGetRequest($request, 'limit', DefaultValues::QUERY_DEFAULT_LIMIT);
	    $page = Util::getValueForKeyOnGetRequest($request, 'page', DefaultValues::QUERY_DEFAULT_PAGE);
	    $userId = $this->getCurrentUserId();

        if($type == "init") {
	        $serviceResult = (new FeaturedService())->getFeaturedUsersForInitStart($userId);
	        $jsonView = new GetListOnAppInitJsonView($serviceResult);
        }else if($type == "feed"){
        	//$serviceResult = (new FeaturedService())->getFeaturedUsersForFeed($userId);
	        //$jsonView = new GetListOnFeedJsonView($serviceResult);
	        $serviceResult = (new FeaturedService())->getFeaturedUsersForFeedTmp($userId);
	        $jsonView = new GetListOnFeedTmpJsonView($serviceResult);


        }else if($type == "facebook"){
	        $serviceResult = (new FeaturedService())->getFeaturedUsersForFacebook($userId,$page,$limit);
	        $jsonView = new GetListOnFacebookJsonView($serviceResult);
        }else {
	        $serviceResult = (new FeaturedService())->getFeaturedUsersForPickup($userId);
	        $jsonView = new GetListOnPickupJsonView($serviceResult);
        }
        return $this->responseJson($jsonView);
    }



}