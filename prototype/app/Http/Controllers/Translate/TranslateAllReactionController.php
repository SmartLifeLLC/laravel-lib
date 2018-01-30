<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:53
 */

namespace App\Http\Controllers\Translate;

use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousAllReactionJsonView;
use App\Models\Old\ReactionLog;
use App\Services\Translate\PreviousAllReactionService;
use DB;

class TranslateAllReactionController extends Controller
{
    /**
     * @return array
     */
    public function translatePreviousData(){
        $results = array();

        $contributions = (new ReactionLog())->getData();

        foreach ($contributions as $contribution) {
            $userId = $contribution->user_id;
            $contributionId = $contribution->feed_id;
            $reactionType = $contribution->review_post_reaction_type;
            $created = $contribution->created_at;

            $serviceResult = (new PreviousAllReactionService())->getData($userId, $contributionId, $reactionType, $created);

            $jsonView = (new PreviousAllReactionJsonView($serviceResult));
            $results[] = $this->responseJson($jsonView);
        }
        return $results;
    }
}