<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:39
 */

namespace App\Http\Controllers\Feed;


use App\Constants\DefaultValues;
use App\Constants\PostParametersValidationRule;
use App\Http\Controllers\Controller;
use App\Http\JsonView\Feed\CommentCreateJsonView;
use App\Http\JsonView\Feed\CommentDeleteJsonView;
use App\Http\JsonView\Feed\CommentGetListJsonView;
use App\Models\CurrentUser;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
	public function create(Request $request){
		$validator = $this->createValidator( $request->all(),
			PostParametersValidationRule::PRODUCT_ID,
			PostParametersValidationRule::FEED_ID,
			PostParametersValidationRule::COMMENT_CONTENT
		);

		if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);

		//No use
		$productId = $request->get('product_item_id');

		//parameters
		$userId = (CurrentUser::shared())->getUserId();
		$feedId = $request->get('review_post_id');
		$content = $request->get('text');
		$serviceResult = (new CommentService())->create($userId,$feedId,$content);
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

		$userId = (CurrentUser::shared())->getUserId();
		$serviceResult = (new CommentService())->delete($userId,$commentId);
		return $this->responseJson(new CommentDeleteJsonView($serviceResult));
	}

	/**
	 * @param $feedId
	 * @param $boundaryId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getList($feedId,$boundaryId = 0){
		if(empty($feedId)){
			return $this->responseParameterErrorJsonViewWithDebugMessage("Feed id cannot be empty.");
		}

		$limit = DefaultValues::QUERY_DEFAULT_LIMIT;
		$isAsc = true;
		$serviceResult = (new CommentService())->getList($feedId,$boundaryId,$isAsc,$limit);
		return $this->responseJson(new CommentGetListJsonView($serviceResult));
	}
}