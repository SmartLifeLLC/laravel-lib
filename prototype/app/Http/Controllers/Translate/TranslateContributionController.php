<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 23:00
 */

namespace App\Http\Controllers\Translate;

use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousContributionJsonView;
use App\Models\Old\ReviewPost;
use App\Services\Translate\PreviousContributionService;
use DB;

class TranslateContributionController extends Controller
{
    /**
     * @return null|string
     */
    public function translatePreviousData(){
        $contributions = (new ReviewPost())->getData();

        foreach ($contributions as $contribution) {
            $userId = $contribution->user_id;
            $productId = $contribution->product_item_id;
            $images = explode(',', $contribution->image_ids);
            $content = $contribution->text;
            $created = $contribution->created_at;
            $feeling = $this->getFeeling($contribution->is_consent);

            $serviceResult = (new PreviousContributionService())->getData($userId, $productId, $feeling, $images, $content, $created);

            if($serviceResult->getDebugMessage() != NULL) return $serviceResult->getDebugMessage();
        }
        return 'SUCCESS';
    }

    private function getFeeling($isConsent){
        if($isConsent) $feeling = 'positive';
        else $feeling = 'negative';
        return $feeling;
    }
}