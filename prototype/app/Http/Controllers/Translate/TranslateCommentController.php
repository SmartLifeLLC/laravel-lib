<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/19
 * Time: 18:26
 */

namespace App\Http\Controllers\Translate;


use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousCommentJsonView;
use App\Models\Old\ReviewPostComments;
use App\Services\Translate\PreviousCommentService;
use DB;

class TranslateCommentController extends Controller
{
    /**
     * @return array
     */
    public function translatePreviousData(){
        $results = array();

        $comments = (new ReviewPostComments())->getData();

        foreach ($comments as $comment) {
            $userId = $comment->user_id;
            $contributionId = $comment->feed_id;
            $content = $comment->text;

            $serviceResult = (new PreviousCommentService())->getData($userId, $contributionId, $content);

            $jsonView = (new PreviousCommentJsonView($serviceResult));
            $results[] = $this->responseJson($jsonView);
        }
        return $results;
    }
}