<?php

namespace App\Models;

use App\Constants\DateTimeFormat;
use App\Constants\ImageCategory;
use App\Constants\S3Buckets;
use App\Lib\Util;
use App\Manager\AwsManager;
use Aws\S3\S3Client;
use Illuminate\Database\Eloquent\Model;

class Image extends DBModel
{
    protected $guarded = [];



    /**
     * @param $userId
     * @param $type
     * @param $s3key
     * @return int
     */
    function createGetId(String $userId, String $type, String $s3key){
        $values = ['user_id'=>$userId,'type'=>$type,'s3_key'=>$s3key,'created_at'=> date(DateTimeFormat::General)];
        return $this->insertGetId($values);
    }

    /**
     * @param String $userId
     * @param String $imageType
     * @param String $imageUrl
     * @return int
     */
    function saveImageToS3FromUrlGetId(String $userId, String $imageType, String $imageUrl){
		$s3key = (new AwsManager())->saveImageToS3FromUrlGetS3Key($userId,$imageType,$imageUrl);
        $id = $this->createGetId($userId,$imageType,$s3key);
        return $id;
    }


	/**
	 * @param String $userId
	 * @param String $imageCategory
	 * @param $images
	 * @return Array
	 */
    function saveImagesToS3FromFilesGetIds(String $userId, String $imageCategory, $images):Array{
	    $awsManager = new AwsManager();
	    $imageIds = [];
	    foreach($images as $image){
		    $s3Key= $awsManager->saveImageToS3FromFileGetS3Key($userId,ImageCategory::CONTRIBUTION,$image);
		    //Save image
		    $imageIds[] = $this->createGetId($userId,$imageCategory,$s3Key);
	    }

		return $imageIds;
    }

    /**
     * @param $userId
     * @param $s3Key
     * @param $type
     * @param $created
     * @return mixed
     */
    public function translateGetId($userId, $s3Key, $type, $created)
    {
        return $this->insertGetId(
            [
                'user_id' => $userId,
                's3_key' => $s3Key,
                'type' => $type,
                'created_at' => $created,
                'updated_at' => date(DateTimeFormat::General)

            ]
        );
    }
}
