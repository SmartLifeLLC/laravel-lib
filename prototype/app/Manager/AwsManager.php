<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/17
 * Time: 3:30
 */

namespace App\Manager;


use App\Constants\ConfigConstants;
use App\Constants\S3Buckets;
use App\Lib\Util;
use Aws\S3\S3Client;
use Illuminate\Http\File;

class AwsManager
{

	private static $sharedS3Client = null;

	/**
	 * @return S3Client
	 */
	private function getS3Client():?S3Client{
		if(AwsManager::$sharedS3Client == null ) {
			AwsManager::$sharedS3Client  = new S3Client(
				[
					'version' => 'latest',
					'region' => S3Buckets::REGION
				]
			);
		}
		return AwsManager::$sharedS3Client;
	}

	/**
	 * @param String $userId
	 * @param String $imageType
	 * @param String $imageUrl
	 * @return String
	 */
	public function saveImageToS3FromUrlGetS3Key(String $userId, String $imageType, String $imageUrl){

		$client = $this->getS3Client();
		$imageName = Util::getImageNameWithExtensionFromUrl($imageUrl);
		$s3key = Util::getS3KeyForImageName($userId,$imageName,$imageType);
		$s3SavePoint = Util::getS3SaveUrl($s3key);
		$client->registerStreamWrapper();
		copy($imageUrl,$s3SavePoint);
		return $s3key;

	}

	/**
	 * @param String $userId
	 * @param String $imageCategory
	 * @param File $file
	 * @return String
	 */
	public function saveImageToS3FromFileGetS3Key(String $userId, String $imageCategory, File $file){
		$client = $this->getS3Client();
		$s3key = Util::getS3KeyForImageName($userId,$file->getBasename(),$imageCategory);

		$client->putObject(array(
			'Bucket'       => ConfigConstants::S3ImageBucket(),
			'Key'          => $s3key,
			'SourceFile'   => $file,
			'ContentType'  => $file->getMimeType(),
			'ACL'          => 'public-read',
			'StorageClass' => 'STANDARD'
		));

		return $s3key;
	}

}