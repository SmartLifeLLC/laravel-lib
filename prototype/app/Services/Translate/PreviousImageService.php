<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/30
 * Time: 16:27
 */

namespace App\Services\Translate;

use App\Models\Image;
use App\Services\BaseService;
use App\Lib\JSYService\ServiceResult;
use DB;

class PreviousImageService extends BaseService
{
    /**
     * @param $userId
     * @param $s3Key
     * @param $type
     * @param $created
     * @return ServiceResult
     */
    public function getData($userId, $s3Key, $type, $created):ServiceResult{
        return $this->executeTasks(function() use($userId, $s3Key, $type, $created) {
            $imageId = (new Image())->translateGetId($userId, $s3Key, $type, $created);
            return ServiceResult::withResult($imageId);
        },true);
    }
}