<?php

namespace App\Http\Middleware;

use App\Constants\AuthAllowedUrls;
use App\Constants\HeaderKeys;
use App\Constants\StatusCode;
use App\Constants\StatusMessage;
use App\Constants\Versions;
use App\Http\JsonResponseView;
use App\Lib\Logger;
use App\Services\AuthService;
use App\Services\UserService;
use Closure;
use Log;

use App\Http\Middleware\Request;

class ReactApiAuthCheck
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

        $appVersion = $request->header(HeaderKeys::REACT_VERSION);

        //旧バージョンではauth検査をしない
        //旧バージョンのユーザがなくなったらなくす。
        if($appVersion == Versions::AUTH_ALLOW_VERSION){
            return $next($request);
        }

        $path = $request->path();
        $authAllowedUrls = AuthAllowedUrls::LIST;
        foreach ($authAllowedUrls as $url){
            //Pass the url.
            if(strpos($path,$url)!==false) {
                return $next($request);
            }
        }
        $userId = $request->header(HeaderKeys::REACT_USER_ID);
        $auth = $request->header(HeaderKeys::REACT_AUTH);
        $serviceResult = (new AuthService())->isValidAuth($userId,$auth);

        if($serviceResult->getResult() == false){
            $jsonResponse = JsonResponseView::withErrorCode(StatusCode::AUTH_FAILED,"ID and Auth does not match");
            Logger::requestError($request,StatusCode::AUTH_FAILED);
            return response()->json($jsonResponse->createJsonString());
        }

        return $next($request);
    }
}
