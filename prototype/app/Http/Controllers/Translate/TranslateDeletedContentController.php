<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/31
 * Time: 21:36
 */

namespace App\Http\Controllers\Translate;

use App\Http\Controllers\Controller;
use App\Http\JsonView\Translate\PreviousDeletedContentJsonView;
use App\Models\Old\DeletedContent;
use App\Services\Translate\PreviousDeletedContentService;
use DB;

class TranslateDeletedContentController extends Controller
{
    /**
     * @return null|string
     */
    public function translatePreviousData(){
        $contents = (new DeletedContent())->getData();

        foreach ($contents as $content) {
            $targetId = $content->target_id;
            $targetTable = $content->target_table;
            $userId = $content->user_id;
            $content = $content->contents_detail;
            $relatedContent = $content->related_data;
            $created = $content->created_at;

            $serviceResult = (new PreviousDeletedContentService())->getData($targetId, $targetTable, $userId, $content, $relatedContent, $created);

            if($serviceResult->getDebugMessage() != NULL) return $serviceResult->getDebugMessage();
        }
        return 'SUCCESS';
    }
}