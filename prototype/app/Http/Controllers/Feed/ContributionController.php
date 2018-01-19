<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 2:53
 */

namespace App\Http\Controllers\Feed;


use App\Constants\FeedFeelingType;
use App\Constants\PostParametersValidationRule;
use App\Http\Controllers\Controller;
use App\Http\JsonView\Feed\ContributionCreateJsonView;
use App\Http\JsonView\Feed\ContributionEditJsonView;
use App\Http\JsonView\Feed\ContributionFindJsonView;
use App\Models\CurrentUser;
use App\Services\ContributionService;
use Illuminate\Http\Request;

class ContributionController extends Controller
{

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function create(Request $request){
		$validator = $this->createValidator( $request->all(),
			PostParametersValidationRule::PRODUCT_ID,
			PostParametersValidationRule::PRODUCT_FEED_TYPE,
			PostParametersValidationRule::IMAGE1,
			PostParametersValidationRule::IMAGE2,
			PostParametersValidationRule::IMAGE3,
			PostParametersValidationRule::IMAGE4
		) ;
		if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);

		$imageKeys = array("image1", "image2", "image3", "image4");
		$images = [];
		foreach($imageKeys as $key) {
			$image = $request->get($key);
			if(!empty($image)){
				$images[] = $image;
			}
		}
		$userId = CurrentUser::shared()->getUserId();
		$productId = $request->get('product_item_id');
		$feedFeelingType = ($request->get('is_consent') == 0)?FeedFeelingType::NEGATIVE:FeedFeelingType::POSITIVE;
		$content =  $request->get('text');
		$haveReactionTargetFeedId = $request->get('to_having_reaction_review_post_id',null);
		$serviceResult = (new ContributionService())->create($userId,$productId,$feedFeelingType,$content,$images,$haveReactionTargetFeedId);
		return $this->responseJson(new ContributionCreateJsonView($serviceResult));
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function edit(Request $request){
		$validator = $this->createValidator( $request->all(),
			PostParametersValidationRule::FEED_ID,
			PostParametersValidationRule::FEED_CONTENT
		) ;

		if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);
		$userId = CurrentUser::shared()->getUserId();
		$feedId  = $request->get('review_post_id');
		$content =  $request->get('text');
		$serviceResult = (new ContributionService())->edit($userId,$feedId,$content);
		return $this->responseJson(new ContributionEditJsonView($serviceResult));
	}


	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function find(Request $request){
		$validator = $this->createValidator( $request->all(),
			PostParametersValidationRule::PRODUCT_ID
		);
		if($validator->fails()) return  $this->responseParameterErrorJsonViewWithValidator($validator);
		$userId = CurrentUser::shared()->getUserId();
		$productId = $request->get('product_item_id');
		$serviceResult = (new ContributionService())->find($userId,$productId);
		return $this->responseJson(new ContributionFindJsonView($serviceResult));

	}

}