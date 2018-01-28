<?php

namespace App\Http\Controllers;

use App\Constants\DefaultValues;
use App\Constants\HeaderKeys;
use App\Constants\StatusCode;
use App\Constants\PostParametersValidationRule;
use App\Http\JsonView\JsonResponseErrorView;
use App\Http\JsonView\JsonResponseView;
use App\Lib\Logger;
use App\Models\CurrentHeaders;
use App\Models\CurrentUser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * @param JsonResponseView $jsonResponseView
     * @return JsonResponse
     */
    protected function responseJson(JsonResponseView $jsonResponseView):JsonResponse{
    	return response()->json($jsonResponseView->getResponse());
    }


    /**
     * @param $parameters
     * @param array ...$targets
     * @return mixed
     */
    protected function createValidator($parameters, ...$targets){
        $data = PostParametersValidationRule::get($targets);
        return Validator::make($parameters, $data);
    }

    /**
     * @param $validator
     * @return JsonResponse
     */
    protected function responseParameterErrorJsonViewWithValidator($validator){
        return $this->responseParameterErrorJsonViewWithDebugMessage("Failed with following rules : ".json_encode($validator->failed()));
    }

    /**
     * @param $debugMessage
     * @return JsonResponse
     */
    protected function responseParameterErrorJsonViewWithDebugMessage($debugMessage){
        $jsonResponseView = new JsonResponseErrorView(
            StatusCode::REQUEST_PARAMETER_ERROR,
            $debugMessage
        );
        Logger::parameterError($debugMessage);
        return $this->responseJson($jsonResponseView);
    }

	/**
	 * @return int
	 */
    protected function getCurrentUserId(){
    	return CurrentUser::shared()->getUserId();
    }
}
