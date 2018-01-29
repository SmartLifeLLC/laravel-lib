<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/18
 * Time: 12:48
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
use App\Constants\DefaultValues;
use App\Constants\QueryOrderTypes;
use App\Lib\Util;
use App\Models\Common\DeleteAllForContributionImplements;
use App\Models\Common\DeleteAllForContributionInterface;

class ContributionComment extends DBModel implements DeleteAllForContributionInterface
{
	use DeleteAllForContributionImplements;

	/**
	 * @param $userId
	 * @param $contributionId
	 * @param $content
	 * @return mixed
	 */
	function createGetId($userId, $contributionId, $content){
		return $this->insertGetId(
			[
				'user_id'=>$userId,
				'contribution_id'=>$contributionId,
				'content'=>$content,
				'created_at'=>date(DateTimeFormat::General),
				'updated_at'=>date(DateTimeFormat::General)

			]
		);
	}

    /**
     * @param $userId
     * @param $contributionId
     * @param $content
     * @param $created
     * @return mixed
     */
    function translateGetId($userId, $contributionId, $content, $created){
        return $this->insertGetId(
            [
                'user_id'=>$userId,
                'contribution_id'=>$contributionId,
                'content'=>$content,
                'created_at'=>$created,
                'updated_at'=>date(DateTimeFormat::General)

            ]
        );
    }

	/**
	 * @param int $contributionId
	 * @param int $boundaryId
	 * @param array $blockUsers
	 * @param int $limit
	 * @param QueryOrderTypes|null $orderType
	 * @return array
	 */
	public function getList(int $contributionId, int $boundaryId, array $blockUsers, int $limit, ?QueryOrderTypes $orderType ) {
		$compareSymbol = $orderType->getQueryCompareSymbol();
		$queryBuilder =
			$this
				->select(
					[
						'contribution_comments.id',
						'contribution_comments.content',
						'contribution_comments.created_at',
						'contribution_comments.updated_at',
						'contribution_comments.user_id',
						'users.user_name',
						'users.description',
						'users.gender',
						'users.birthday',
						'users.gender_published_flag',
						'users.birthday_published_flag',
						'profile_image.s3_key as profile_image_s3_key',
						'cover_image.s3_key as cover_image_s3_key',
						'contributions.product_id',
						'contributions.id as contribution_id',
					])
				->where('contribution_id',$contributionId);
		if($boundaryId > 0){
			$queryBuilder = $queryBuilder->where('id',$compareSymbol,$boundaryId);
		}

		if(!empty($blockUsers)){
			$blockUsersString = implode(',',$blockUsers);
			$queryBuilder
				->whereRaw(DB::raw("NOT EXISTS(select * from users as block_users where block_users.id in ({$blockUsersString}) and users.id = block_users.id)"));

		}

		$queryBuilder =
			$queryBuilder
			->leftJoin('contributions','contributions.id','=','contribution_comments.contribution_id')
			->leftJoin('users','users.id','=','contribution_comments.user_id')
			->leftJoin('images as profile_image','profile_image.id','=','users.profile_image_id')
			->leftJoin('images as cover_image','cover_image.id','=','users.cover_image_id');


		$result = $queryBuilder->orderBy('id', $orderType->getValue())->limit($limit)->get();
		if($result === null)
			return [];
		else
			return $result;

	}

	/**
	 * @param $contributionId
	 * @return mixed
	 */
	public function getCountForContribution($contributionId){
		return $this->where('contribution_id',$contributionId)->count();
	}

	/**
	 * @param $contributionId
	 * @param int $limit
	 * @return mixed
	 */
	public function getPureListForContribution($contributionId, $limit = DefaultValues::QUERY_DEFAULT_LIMIT){
		return $this->where('contribution_id',$contributionId)->limit($limit)->get();
	}

	/**
	 * @param $contributionIds
	 * @param $userIds
	 * @return mixed
	 */
	public function getListForContributionsAndUsers($contributionIds,$userIds){
		return
			$this
				->whereIn('contribution_id',$contributionIds)
				->whereIn('user_id',$userIds)
				->get();

	}
}