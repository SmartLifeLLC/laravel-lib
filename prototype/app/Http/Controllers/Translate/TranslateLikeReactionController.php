<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:29
 */

namespace App\Http\Controllers\Translate;

use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousLikeReactionJsonView;
use App\Models\Old\ReactionLog;
use App\Services\Translate\PreviousLikeReactionService;
use DB;

class TranslateLikeReactionController extends Controller
{
    /**
     * @return array
     */
    public function translatePreviousData(){
        $results = array();

        $contributions = (new ReactionLog())->getLikeData();

        foreach ($contributions as $contribution) {
            $userId = $contribution->user_id;
            $contributionId = $contribution->feed_id;
            $created = $contribution->created_at;

            $serviceResult = (new PreviousLikeReactionService())->getData($userId, $contributionId, $created);

            $jsonView = (new PreviousLikeReactionJsonView($serviceResult));
            $results[] = $this->responseJson($jsonView);
        }
        return $results;
    }
}