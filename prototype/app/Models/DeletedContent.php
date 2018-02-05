<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 17:03
 */

namespace App\Models;


use App\Constants\DateTimeFormat;

class DeletedContent extends DBModel
{

	/**
	 * @param $userId
	 * @param $targetTable
	 * @param $targetId
	 * @param $content
	 * @param $relatedContent
	 * @return mixed
	 */
	public function createGetId($userId, $targetTable, $targetId, $content, $relatedContent){
		if(is_array($content))
			$content = json_encode($content);
		if(is_array($relatedContent))
			$relatedContent = json_encode($relatedContent);

		$data = [
			'user_id'=>$userId,
			'target_table'=>$targetTable,
			'target_id'=>$targetId,
			'content' => $content,
			'related_content'=>$relatedContent,
			'created_at' => date(DateTimeFormat::General)
			];
		return $this->insertGetId($data);
	}

	public function translateGetId($id, $targetId, $targetTable, $userId, $content, $relatedContent, $created){
        return $this->insertGetId(
            [
                'id'=>$id,
                'target_id'=>$targetId,
                'target_table'=>$targetTable,
                'user_id'=>$userId,
                'content'=>$content,
                'related_content'=>$relatedContent,
                'created_at'=>$created
            ]
        );
	}
}