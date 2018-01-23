<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/19
 * Time: 18:26
 */

namespace App\Http\Controllers\Translate;


use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousCommentJsonView;
use App\Services\Translate\PreviousCommentService;

class TranslateCommentController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function translatePreviousData(){
        $serviceResult = (new PreviousCommentService())->getData();
        $jsonView = new PreviousCommentJsonView($serviceResult);
        return $this->responseJson($jsonView);
    }
}