<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 13:45
 */

namespace App\Http\Controllers\Feed;


use App\Constants\DefaultValues;
use App\Constants\FeedReactionType;
use App\Constants\PostParametersValidationRule;
use App\Http\Controllers\Controller;
use App\Http\JsonView\Feed\ReactionGetListJsonView;
use App\Http\JsonView\Feed\ReactionUpdateJsonView;
use App\Lib\JSYService\ServiceResult;
use App\Models\CurrentUser;
use App\Services\ReactionService;
use App\Services\Tasks\UpdateReactionCountTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReactionController extends Controller
{


	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function doReaction(Request $request):JsonResponse{
		return $this->updateReaction($request,true);
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function cancelReaction(Request $request):JsonResponse{
		return $this->updateReaction($request,false);
	}

	/**
	 * @param Request $request
	 * @param $isIncrease
	 * @return JsonResponse
	 */
	private function updateReaction(Request $request,$isIncrease):JsonResponse{
		$validator = $this->createValidator( $request->all(),
			PostParametersValidationRule::REACTION_TYPE,
			PostParametersValidationRule::FEED_ID
		) ;
		if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);
		$userId = $this->getCurrentUserId();
		$feedId = $request->get('review_post_id');
		$reactionType = $request->get('review_post_reaction_type');

		if($reactionType == FeedReactionType::HAVE)
			return $this->responseParameterErrorJsonViewWithDebugMessage("Have reaction no more permitted");


		$serviceResult = (new ReactionService())->updateReaction($userId,$feedId,$reactionType,$isIncrease);
		return $this->responseJson(new ReactionUpdateJsonView($serviceResult));
	}

	/**
	 * @param Request $request
	 * @param $feedId
	 * @return JsonResponse
	 */
	public function getList(Request $request,$feedId):JsonResponse{
		$userId = $this->getCurrentUserId();
		$type = $request->get('type',FeedReactionType::ALL);
		$page = $request->get('page',DefaultValues::QUERY_DEFAULT_PAGE);
		$limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
		$serviceResult = (new ReactionService())->getList($userId,$feedId,$type,$page,$limit);
		return $this->responseJson(new ReactionGetListJsonView($serviceResult));
	}
}