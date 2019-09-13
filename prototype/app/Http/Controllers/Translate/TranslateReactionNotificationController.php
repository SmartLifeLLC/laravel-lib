<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 22:31
 */

namespace App\Http\Controllers\Translate;

use App\Constants\ContributionReactionType;
use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousReactionNotificationJsonView;
use App\Models\Old\NotificationLog;
use App\Services\Translate\PreviousReactionNotificationService;
use DB;

class TranslateReactionNotificationController extends Controller
{
    /**
     * @return null|String
     */
    public function translatePreviousData(){
        $logs = (new NotificationLog())->getData();

        foreach ($logs as $log) {
            $type = $this->getTypeId($log->type);
            if($type == 0) continue;
            $userId = $log->user_id;
            $created = $log->created_at;

            $detail = json_decode($log->detail_info, true);
            $contributionId = $detail['review_post_id'];

            $serviceResult = (new PreviousReactionNotificationService())->getData($userId, $contributionId, $type, $created);

            if ($serviceResult->getDebugMessage() != NULL) return $serviceResult->getDebugMessage();
        }
        return 'SUCCESS';
    }

    /**
     * @param $type
     * @return int
     */
    private function getTypeId($type){
        switch($type){
            case 'like':
                return ContributionReactionType::LIKE;
            case 'have':
                return ContributionReactionType::HAVE;
            case 'interest':
                return ContributionReactionType::INTEREST;
            default:
                return 0;
        }
    }
}