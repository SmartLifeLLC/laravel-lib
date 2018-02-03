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
        $deletedContents = (new DeletedContent())->getData();

        foreach ($deletedContents as $deletedContent) {
            $id = $deletedContent->id;
            $targetId = $deletedContent->target_id;
            $targetTable = $deletedContent->target_table;
            $userId = $deletedContent->user_id;
            $content = $deletedContent->contents_detail;
            $relatedContent = $deletedContent->related_data;
            $created = $deletedContent->created_at;

            $serviceResult = (new PreviousDeletedContentService())->getData($id, $targetId, $targetTable, $userId, $content, $relatedContent, $created);

            if($serviceResult->getDebugMessage() != NULL) return $serviceResult->getDebugMessage();
        }
        return 'SUCCESS';
    }
}