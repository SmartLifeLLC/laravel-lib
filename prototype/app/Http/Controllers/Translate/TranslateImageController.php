<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/29
 * Time: 21:12
 */

namespace App\Http\Controllers\Translate;

use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousImageJsonView;
use App\Models\Old\Content;
use App\Services\Translate\PreviousImageService;
use DB;

class TranslateImageController extends Controller
{
    /**
     * @return array
     */
    public function translatePreviousData(){
        $results = array();

        $images = (new Content())->getData();

        foreach ($images as $image) {
            $userId = $image->user_id;
            $s3Key = $image->s3_key;
            $type = $image->type;
            $created = $image->created_at;

            $serviceResult = (new PreviousImageService())->getData($userId, $s3Key, $type, $created);

            $jsonView = (new PreviousImageJsonView($serviceResult));
            $results[] = $this->responseJson($jsonView);
        }
        return $results;
    }
}