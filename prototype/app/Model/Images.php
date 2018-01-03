<?php

namespace App\Models;

use App\Constants\ConfigConstants;
use App\Constants\S3Buckets;
use App\Lib\Util;
use Aws\S3\S3Client;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $fillable = ['id', 'type', 'user_id', 's3_key', 'created_at'];
    protected $table = 'contents';
    public $timestamps = true;


    /**
     * @param $userId
     * @param $type
     * @param $s3key
     * @return int
     */
    function saveAndGetId(String $userId, String $type, String $s3key){
        $values = ['user_id'=>$userId,'type'=>$type,'s3_key'=>$s3key];
        return $this->insertGetId($values);
    }

    /**
     * @param String $userId
     * @param String $imageType
     * @param String $imageUrl
     * @return int
     */
    function copyImageUrlToS3AndGetId(String $userId, String $imageType, String $imageUrl){
        //Update image to s3
        $client = new S3Client(
            [
                'version'     => 'latest',
                'region'      => S3Buckets::REGION
            ]
        );

        $imageName = Util::getImageNameFromUrl($imageUrl);
        $s3key = Util::getS3KeyForImageName($imageName,$imageType);
        $s3SavePoint = Util::getS3SavePoint($s3key);
        // Save to S3
        $client->registerStreamWrapper();
        copy($imageUrl,$s3SavePoint);
        // DB store
        $id = $this->saveAndGetId($userId,$imageType,$s3key);
        return $id;
    }

}
