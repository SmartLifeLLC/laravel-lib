<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 13:45
 */

namespace App\Http\Controllers;


use App\Constants\DefaultValues;
use App\Constants\ContributionReactionType;
use App\Constants\PostParametersValidationRule;
use App\Http\Controllers\Controller;
use App\Http\JsonView\Contribution\ReactionGetListJsonView;
use App\Http\JsonView\Contribution\ReactionUpdateJsonView;
use App\Http\JsonView\JsonResponseView;
use App\Lib\JSYService\ServiceResult;
use App\Models\CurrentUser;
use App\Services\ReactionService;
use App\Services\Tasks\UpdateReactionCountTask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Json;

class ReactionController extends Controller
{


	/**
	 * @param Request $request
	 * @param $isOn
	 * @return JsonResponse
	 */
	public function edit(Request $request,$isOn):JsonResponse{
		if($isOn)
			return $this->doReaction($request);
		else
			return $this->cancelReaction($request);
	}

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
			PostParametersValidationRule::CONTRIBUTION_ID
		) ;
		if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);
		$userId = $this->getCurrentUserId();
		$contributionId = $request->get('contribution_id');
		$reactionType = $request->get('contribution_reaction_type');

		if($reactionType == ContributionReactionType::HAVE)
			return $this->responseParameterErrorJsonViewWithDebugMessage("Have reaction no more permitted");


		$serviceResult = (new ReactionService())->updateReaction($userId,$contributionId,$reactionType,$isIncrease);
		return $this->responseJson(new ReactionUpdateJsonView($serviceResult));
	}

	/**
	 * @param Request $request
	 * @param $contributionId
	 * @return JsonResponse
	 */
	public function getList(Request $request, $contributionId):JsonResponse{
		$userId = $this->getCurrentUserId();
		$type = $request->get('type',ContributionReactionType::ALL);
		$page = $request->get('page',DefaultValues::QUERY_DEFAULT_PAGE);
		$limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
		$serviceResult = (new ReactionService())->getList($userId,$contributionId,$type,$page,$limit);
		return $this->responseJson(new ReactionGetListJsonView($serviceResult));
	}
}