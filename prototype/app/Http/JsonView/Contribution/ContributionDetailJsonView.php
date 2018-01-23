<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 5:51
 */

namespace App\Http\JsonView\Contribution;


use App\Http\JsonView\JsonResponseView;
use App\ValueObject\ContributionDetailResultVO;

class ContributionDetailJsonView extends JsonResponseView
{
	/**
	 * @var ContributionDetailResultVO
	 */
	protected $data;
	function createBody()
	{
		$contribution = $this->data->getContribution();
		$product = $this->data->getProduct();
		$categories = $this->data->getProductCategories();
		$imageUrls = $this->getImageURLs($contribution['image_id_0'],$contribution['image_id_1'],$contribution['image_id_2'],$contribution['image_id_3']);
		$body =
			[
				"review_post" =>
					[
						'id' => $contribution['contribution_id'],
						'text' => $contribution['content'],
						'is_consent' => $this->getFeelingBinaryValue($contribution['feeling']),
						'product_item_id' => $product['product_id'],
						'created_at' => $contribution['created_at'],
						'total_reaction_count'=> $contribution['total_reaction_count'],
						'like_reaction_count'=> $contribution['like_reaction_count'],
						'interest_reaction_count'=> $contribution['interest_reaction_count'],
						'have_reaction_count'=> $contribution['have_reaction_count'],
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
								'introduction' => $contribution['description'],
								'gender' => $this->getGenderString($contribution['gender'],$contribution['gender_published_flag']),
								'birthday' => $this->getBirthdayString($contribution['birthday'],$contribution['birthday_published_flag']),
								"is_following" => $this->getBinaryValue($contribution['follow_id'])
							]
					],
				"product_item" =>
					[
						"shops" => [],
						"id" => $product['id'],
						"name" => $product['display_name'],
						"text" => "",
						"price" => $product['price'],
						"review_post_num" => $product['contribution_count'],
						"consent_num" => $product['positive_count'],
						"refusal_num" => $product['negative_count'],
						"categories" => $categories,
					]
			];
		$this->body = $body;
	}
}