<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 20:38
 */
namespace App\Http\Controllers\Featured;
use App\Constants\DefaultValues;
use App\Http\JsonView\Recommend\FeaturedUser\GetListOnFeedJsonView;
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
        	$serviceResult = (new FeaturedService())->getFeaturedUsersForFeedTmp($userId);
        	$jsonView = new GetListOnFeedJsonView($serviceResult);
        }




        return $this->responseJson($jsonView);
    }



}