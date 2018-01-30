<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:30
 */

namespace App\Http\Controllers\Translate;

use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousInterestReactionJsonView;
use App\Models\Old\ReactionLog;
use App\Services\Translate\PreviousInterestReactionService;
use DB;

class TranslateInterestReactionController extends Controller
{
    /**
     * @return array
     */
    public function translatePreviousData(){
        $results = array();

        $contributions = (new ReactionLog())->getInterestData();

        foreach ($contributions as $contribution) {
            $userId = $contribution->user_id;
            $contributionId = $contribution->feed_id;
            $created = $contribution->created_at;

            $serviceResult = (new PreviousInterestReactionService())->getData($userId, $contributionId, $created);

            $jsonView = (new PreviousInterestReactionJsonView($serviceResult));
            $results[] = $this->responseJson($jsonView);
        }
        return $results;
    }
}