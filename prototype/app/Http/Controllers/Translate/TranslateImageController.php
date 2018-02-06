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
            $id = $image->id;
            $userId = $image->user_id;
            $s3Key = $image->s3_key;
            $created = $image->created_at;
            $type = $this->changeType($image->type);

            $serviceResult = (new PreviousImageService())->getData($id, $userId, $s3Key, $type, $created);

            if ($serviceResult->getDebugMessage() != NULL) return $serviceResult->getDebugMessage();
        }
        return 'SUCCESS';
    }

    private function changeType($type){
        if($type == 'review_post') $new_type = 'contribution';
        else $new_type = $type;
        return $new_type;
    }
}