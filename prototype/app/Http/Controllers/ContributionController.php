<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 2:53
 */

namespace App\Http\Controllers;


use App\Constants\DefaultValues;
use App\Constants\ContributionFeelingType;
use App\Constants\PostParametersValidationRule;
use App\Http\Controllers\Controller;
use App\Http\JsonView\Feed\ContributionCreateJsonView;
use App\Http\JsonView\Feed\ContributionDeleteJsonView;
use App\Http\JsonView\Feed\ContributionEditJsonView;
use App\Http\JsonView\Feed\ContributionFindJsonView;
use App\Http\JsonView\Feed\ContributionDetailJsonView;
use App\Http\JsonView\Feed\ContributionListJsonView;
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
			PostParametersValidationRule::CONTRIBUTION_FEELING_TYPE,
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
		$userId = $this->getCurrentUserId();
		$productId = $request->get('product_item_id');
		$feedFeelingType = ($request->get('is_consent') == 0)?ContributionFeelingType::NEGATIVE:ContributionFeelingType::POSITIVE;
		$content =  $request->get('text');
		$haveReactionTargetFeedId = $request->get('to_having_reaction_review_post_id',null);
		$serviceResult = (new ContributionService())->create($userId,$productId,$feedFeelingType,$content,$images,$haveReactionTargetFeedId);
		return $this->responseJson(new ContributionCreateJsonView($serviceResult));
	}

	/**
	 * @param Request $request
	 * @param $feedId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function edit(Request $request,$feedId){
		$userId = $this->getCurrentUserId();
		$content =  $request->get('text');
		$serviceResult = (new ContributionService())->edit($userId,$feedId,$content);
		return $this->responseJson(new ContributionEditJsonView($serviceResult));
	}


	/**
	 * @param $productId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function find($productId){
		$userId = $this->getCurrentUserId();
		$serviceResult = (new ContributionService())->find($userId,$productId);
		return $this->responseJson(new ContributionFindJsonView($serviceResult));

	}

	/**
	 * @param $feedId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function detail($feedId){
		$userId = $this->getCurrentUserId();
		$serviceResult = (new ContributionService())->detail($userId,$feedId);
		return $this->responseJson(new  ContributionDetailJsonView($serviceResult));
	}

	/**
	 * @param $feedId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete($feedId){
		$userId = $this->getCurrentUserId();
		$serviceResult = (new ContributionService())->delete($userId,$feedId);
		return $this->responseJson(new ContributionDeleteJsonView($serviceResult));
	}

	/**
	 * @param Request $request
	 * @param $productId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function listForProduct(Request $request,$productId){
		$userId = $this->getCurrentUserId();
		$page = $request->get('page',DefaultValues::QUERY_DEFAULT_PAGE);
		$limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
		$type = $request->get('type',ContributionFeelingType::ALL);
		$serviceResult = (new ContributionService())->getListForProduct($userId,$productId,$type,$page,$limit);
		return $this->responseJson(new ContributionListJsonView($serviceResult));
	}


	/**
	 * @param Request $request
	 * @param $ownerId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function listForOwnerInterest(Request $request,$ownerId){
		$userId = $this->getCurrentUserId();
		$page = $request->get('page',DefaultValues::QUERY_DEFAULT_PAGE);
		$limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
		$serviceResult = (new ContributionService())->getListForOwnerInterest($userId,$ownerId,$page,$limit);
		return $this->responseJson(new ContributionListJsonView($serviceResult));
	}

	/**
	 * @param Request $request
	 * @param $ownerId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function listForOwner(Request $request,$ownerId){
		$userId = $this->getCurrentUserId();
		$page = $request->get('page',DefaultValues::QUERY_DEFAULT_PAGE);
		$limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
		$serviceResult = (new ContributionService())->getListForOwner($userId,$ownerId,$page,$limit);
		return $this->responseJson(new ContributionListJsonView($serviceResult));
	}

	public function listForFeed(Request $request){

	}
}