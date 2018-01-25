<?php

namespace App\Http\Middleware;

use App\Constants\AuthAllowedUrls;
use App\Constants\DefaultValues;
use App\Http\JsonView\User\Auth\UserValidationJsonView;
use App\Models\CurrentHeaders;
use App\Models\CurrentUser;
use App\Constants\HeaderKeys;
use App\Constants\StatusCode;
use App\Constants\StatusMessage;
use App\Constants\Versions;
use App\Http\JsonView\JsonResponseErrorView;
use App\Lib\Logger;
use App\Services\AuthService;
use App\Services\UserService;
use Closure;
use Log;

use App\Http\Middleware\Request;

class ReactApiAuthChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

    	CurrentHeaders::shared()->setHeaders($request->headers);
        $appVersion = CurrentHeaders::shared()->get(HeaderKeys::REACT_VERSION);

        //旧バージョンではauth検査をしない
        //旧バージョンのユーザがなくなったらなくす。
        if($appVersion == Versions::AUTH_ALLOW_VERSION){
            return $next($request);
        }

        $path = $request->path();
        $authAllowedUrls = AuthAllowedUrls::LIST;
        $currentMethod = mb_strtoupper($request->method());
        foreach ($authAllowedUrls as $urlInfo){
            //Pass the url.
            if(strpos($path,$urlInfo['url'])!==false && $urlInfo['method'] == $currentMethod ) {
                return $next($request);
            }
        }

        $userId = $request->header(HeaderKeys::REACT_USER_ID);
        $auth = $request->header(HeaderKeys::REACT_AUTH);
        //Check auth and user limitation for the current http method.
        $serviceResult = (new AuthService())->isValidUser($userId,$auth,$request->method());
        if(empty($serviceResult->getResult())){
	        $jsonResponseView = new UserValidationJsonView($serviceResult);
	        return response()->json($jsonResponseView->getResponse());
        }

        $currentUser = CurrentUser::shared();
        $currentUser->setUserId($userId);
        $currentUser->setAuth($auth);

	    return $next($request)
		    ->header('Access-Control-Allow-Origin', '*')
		    ->header('Access-Control-Allow-Headers','react-auth');

    }
}
