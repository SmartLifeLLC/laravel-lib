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
use App\Models\Old\ReviewPostComment;
use App\Services\Translate\PreviousCommentService;
use DB;

class TranslateCommentController extends Controller
{
    /**
     * @return null|String
     */
    public function translatePreviousData(){
        $comments = (new ReviewPostComment())->getData();

        foreach ($comments as $comment) {
            $userId = $comment->user_id;
            $contributionId = $comment->review_post_id;
            $content = $comment->text;
            $created = $comment->created_at;

            $serviceResult = (new PreviousCommentService())->getData($userId, $contributionId, $content, $created);

            if($serviceResult->getDebugMessage() != NULL) return $serviceResult->getDebugMessage();
        }
        return 'SUCCESS';
    }
}