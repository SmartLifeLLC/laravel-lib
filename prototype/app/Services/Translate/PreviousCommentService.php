<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/21
 * Time: 21:50
 */

namespace App\Services\Translate;


use App\Models\FeedComment;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;

class PreviousCommentService extends BaseService
{
    /**
     * @param $userId
     * @return ServiceResult
     */
    public function getData():ServiceResult{
        return $this->executeTasks(
            function() : ServiceResult{
                $comment = (new ReviewPostComment())->getData();
                while(!empty($comment)){
                    $feed_comment = new FeedComment();
                    $feed_comment->create($comment);

                }

//                $serviceResult = (empty($comment))?
//                    ServiceResult::withError(StatusCode::UNKNOWN_USER_ID," is not found"):
//                    ServiceResult::withResult($comment);
//                return $serviceResult;
            });
    }
}