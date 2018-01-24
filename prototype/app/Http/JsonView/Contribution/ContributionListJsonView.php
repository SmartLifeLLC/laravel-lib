<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 23:38
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;
use App\ValueObject\ContributionListResultVO;

class ContributionListJsonView extends JsonResponseView
{
	/**
	 * @var ContributionListResultVO
	 */
	protected $data;
	function createBody()
	{


		$contributions = $this->data->getContributions();


		$categories = $this->data->getProductsCategories();

		$productsCategories = [];
		foreach($categories as $category){
			$productsCategories[$category['product_id']][$category['product_category_id']] =
				[
					'product_category_id' => $category['product_category_id'],
					'name' => $category['name'],
					'breadcrumb' => $category['unique_name']
				];

		}

		$body =[];
		foreach($contributions as $contribution){
			$imageUrls = $this->getImageURLs($contribution['image_id_0'],$contribution['image_id_1'],$contribution['image_id_2'],$contribution['image_id_3']);
			$displayData =
				["review_post"=>
					[
						'id' => $contribution['contribution_id'],
						'text' => $contribution['content'],
						'is_consent' => $this->getFeelingBinaryValue($contribution['feeling']),
						'product_item_id' => $contribution['product_id'],
						'created_at' => $contribution['contribution_created_at'],
						'total_reaction_count'=> (int) $contribution['total_reaction_count'],
						'like_reaction_count'=> (int) $contribution['like_reaction_count'],
						'interest_reaction_count'=> (int) $contribution['interest_reaction_count'],
						'have_reaction_count'=> (int) $contribution['have_reaction_count'],
						'comment_num' => (int) $contribution['comment_count'],
						'image_urls' => $imageUrls,
						'review_post_id' => $contribution['id'],
						'is_like' => $this->getBinaryValue($contribution['contribution_like_reaction_id']),
						'is_interest' => $this->getBinaryValue($contribution['contribution_interest_reaction_id']),
						'is_having' => $this->getBinaryValue($contribution['contribution_have_reaction_id']),
						"is_review_posted" => $this->getBinaryValue($contribution['my_contribution_id']),
						'post_user_info' =>
							[
								'user_id' => $contribution['user_id'],
								'user_name' => $contribution['user_name'],
								'profile_image_url' => $this->getImageURLForS3Key($contribution['profile_image_s3_key']),
								'cover_image_url' => $this->getImageURLForS3Key($contribution['cover_image_s3_key']),
								'introduction' => $this->getNotNullString($contribution['description']),
								'gender' => $this->getGenderString($contribution['gender'],$contribution['gender_published_flag']),
								'birthday' => $this->getBirthdayString($contribution['birthday'],$contribution['birthday_published_flag']),
								"is_following" => $this->getBinaryValue($contribution['follow_id'])
							]
					],
				"product_item" =>
					[
						"shops" => [],
						"id" => $contribution['product_id'],
						"name" => $contribution['display_name'],
						"text" => "",
						"price" => $contribution['price'],
						"review_post_num" => $contribution['contribution_count'],
						"consent_num" => $contribution['positive_count'],
						"refusal_num" => $contribution['negative_count'],
						"categories" => $productsCategories[$contribution['product_id']],
					]
				];
			$body[] = $displayData;
		}
		$this->body = $body;
	}

}