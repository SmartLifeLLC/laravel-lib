<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:39
 */

namespace App\Http\Controllers;


use App\Constants\DefaultValues;
use App\Constants\PostParametersValidationRule;
use App\Http\Controllers\Controller;
use App\Http\JsonView\Contribution\CommentCreateJsonView;
use App\Http\JsonView\Contribution\CommentDeleteJsonView;
use App\Http\JsonView\Contribution\CommentGetListJsonView;
use App\Models\CurrentUser;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
	public function create(Request $request){
		$validator = $this->createValidator( $request->all(),
			PostParametersValidationRule::CONTRIBUTION_ID,
			PostParametersValidationRule::COMMENT_CONTENT
		);

		if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);

		//No use
		$productId = $request->get('product_item_id');

		//parameters
		$userId = $this->getCurrentUserId();
		$contributionId = $request->get('contribution_id');
		$content = $request->get('text');
		$serviceResult = (new CommentService())->create($userId,$contributionId,$content);
		return $this->responseJson(new CommentCreateJsonView($serviceResult));
	}

	/**
	 * @param $commentId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete($commentId){
		if(empty($commentId)){
			return $this->responseParameterErrorJsonViewWithDebugMessage("Comment id cannot be empty.");
		}

		$userId = $this->getCurrentUserId();
		$serviceResult = (new CommentService())->delete($userId,$commentId);
		return $this->responseJson(new CommentDeleteJsonView($serviceResult));
	}

	/**
	 * @param $contributionId
	 * @param $boundaryId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getList($contributionId, $boundaryId = 0){
		if(empty($contributionId)){
			return $this->responseParameterErrorJsonViewWithDebugMessage("Contribution id cannot be empty.");
		}
		$userId = $this->getCurrentUserId();
		$limit = DefaultValues::QUERY_DEFAULT_LIMIT;
		$isAsc = true;
		$serviceResult = (new CommentService())->getList($userId,$contributionId,$boundaryId,$isAsc,$limit);
		return $this->responseJson(new CommentGetListJsonView($serviceResult));
	}
}