<?php

namespace App\Http\Controllers;

use App\Http\JsonView\JsonResponseView;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    function responseJson(JsonResponseView $jsonFactory):JsonResponse{
        return response()->json($jsonFactory->createJsonString());
    }
}
