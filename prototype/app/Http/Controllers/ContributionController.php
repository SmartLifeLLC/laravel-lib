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
use App\Constants\ListType;
use App\Constants\PostParametersValidationRule;
use App\Http\Controllers\Controller;
use App\Http\JsonView\Contribution\ContributionCreateJsonView;
use App\Http\JsonView\Contribution\ContributionDeleteJsonView;
use App\Http\JsonView\Contribution\ContributionEditJsonView;
use App\Http\JsonView\Contribution\ContributionFindJsonView;
use App\Http\JsonView\Contribution\ContributionDetailJsonView;
use App\Http\JsonView\Contribution\ContributionListJsonView;
use App\Models\BlockUser;
use App\Models\CurrentUser;
use App\Services\ContributionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

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
			$image = $request->file($key);
			if(!empty($image)){
				$images[] = $image;
			}
		}
		$userId = $this->getCurrentUserId();
		$productId = $request->get('product_item_id');
		$contributionFeelingType = ($request->get('is_consent') == 0)?ContributionFeelingType::NEGATIVE:ContributionFeelingType::POSITIVE;
		$content =  $request->get('text');
		$serviceResult = (new ContributionService())->create($userId,$productId,$contributionFeelingType,$content,$images,$haveReactionTargetContributionId);
		return $this->responseJson(new ContributionCreateJsonView($serviceResult));
	}

	/**
	 * @param Request $request
	 * @param $contributionId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function edit(Request $request, $contributionId){
		$userId = $this->getCurrentUserId();
		$content =  $request->get('text');
		$serviceResult = (new ContributionService())->edit($userId,$contributionId,$content);
		return $this->responseJson(new ContributionEditJsonView($serviceResult));
	}


	/**
	 * @param $productId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function check($productId){
		$userId = $this->getCurrentUserId();
		$serviceResult = (new ContributionService())->find($userId,$productId);
		return $this->responseJson(new ContributionFindJsonView($serviceResult));
	}

	/**
	 * @param $contributionId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function get($contributionId){
		$userId = $this->getCurrentUserId();
		$serviceResult = (new ContributionService())->detail($userId,$contributionId);
		return $this->responseJson(new  ContributionDetailJsonView($serviceResult));
	}

	/**
	 * @param $contributionId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function delete($contributionId){
		$userId = $this->getCurrentUserId();
		$serviceResult = (new ContributionService())->delete($userId,$contributionId);
		return $this->responseJson(new ContributionDeleteJsonView($serviceResult));
	}


	/**
	 * @param Request $request
	 * @param null $targetId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function list(Request $request, $targetId = null){
		$listType = $request->get('listType',ListType::CONTRIBUTION_LIST_FEED);
		switch ($listType){
			case ListType::CONTRIBUTION_LIST_FEED:
				return $this->listForFeed($request);break;
			case ListType::CONTRIBUTION_LIST_USER:
				return $this->listForOwner($request,$targetId);break;
			case ListType::CONTRIBUTION_LIST_FOR_USER_INTEREST:
				return $this->listForOwnerInterest($request,$targetId);break;
			case ListType::CONTRIBUTION_LIST_FOR_PRODUCT:
				return $this->listForProduct($request,$targetId);break;
			default:
				return $this->responseParameterErrorJsonViewWithDebugMessage("Failed to find list type for {$listType}");
		}
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
		$feelingType = $request->get('feelingType',ContributionFeelingType::ALL);
		$serviceResult = (new ContributionService())->getListForProduct($userId,$productId,$feelingType,$page,$limit);
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

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function listForFeed(Request $request){
		$userId = $this->getCurrentUserId();
		$page = $request->get('page',DefaultValues::QUERY_DEFAULT_PAGE);
		$limit = $request->get('limit',DefaultValues::QUERY_DEFAULT_LIMIT);
		$serviceResult = (new ContributionService())->getListForFeed($userId,$page,$limit);
		return $this->responseJson(new ContributionListJsonView($serviceResult));
	}
}