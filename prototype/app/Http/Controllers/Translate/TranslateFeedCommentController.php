<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/19
 * Time: 18:26
 */

namespace App\Http\Controllers\Translate;


use App\Http\Controllers\Controller;
use App\Http\JsonView\User\User\PreviousFeedCommentJsonVIew;
use App\Services\PreviousFeedCommentService;

class TranslateFeedCommentController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreviousData(){
        $serviceResult = (new PreviousFeedCommentService())->getData();
        $jsonView = new PreviousFeedCommentJsonView($serviceResult);
        return $this->responseJson($jsonView);
    }
}