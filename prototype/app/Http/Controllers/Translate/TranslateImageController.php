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
     * @return null|string
     */
    public function translatePreviousData(){
        $images = (new Content())->getData();

        foreach ($images as $image) {
            $userId = $image->user_id;
            $s3Key = $image->s3_key;
            $type = $image->type;
            $created = $image->created_at;

            $serviceResult = (new PreviousImageService())->getData($userId, $s3Key, $type, $created);

            if ($serviceResult->getDebugMessage() != NULL) return $serviceResult->getDebugMessage();
        }
        return 'SUCCESS';
    }
}