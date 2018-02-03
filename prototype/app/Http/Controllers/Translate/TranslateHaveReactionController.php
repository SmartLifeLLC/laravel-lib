<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:30
 */

namespace App\Http\Controllers\Translate;

use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousHaveReactionJsonView;
use App\Models\Old\ReactionLog;
use App\Services\Translate\PreviousHaveReactionService;

class TranslateHaveReactionController extends Controller
{
    /**
     * @return null|string
     */
    public function translatePreviousData(){
        $contributions = (new ReactionLog())->getHaveData();

        foreach ($contributions as $contribution) {
            $userId = $contribution->reaction_user_id;
            $contributionId = $contribution->review_post_id;
            $created = $contribution->created_at;

            $serviceResult = (new PreviousHaveReactionService())->getData($userId, $contributionId, $created);

            if ($serviceResult->getDebugMessage() != NULL) return $serviceResult->getDebugMessage();
        }
        return 'SUCCESS';
    }
}