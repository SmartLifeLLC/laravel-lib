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
     * @return null|string
     */
    public function translatePreviousData(){
        $contributions = (new ReactionLog())->getData();

        foreach ($contributions as $contribution) {
            $userId = $contribution->reaction_user_id;
            $contributionId = $contribution->review_post_id;
            $reactionType = $contribution->review_post_reaction_type;
            $created = $contribution->created_at;

            $serviceResult = (new PreviousAllReactionService())->getData($userId, $contributionId, $reactionType, $created);

            if($serviceResult->getDebugMessage() != NULL) return $serviceResult->getDebugMessage();
        }
        return 'SUCCESS';
    }
}