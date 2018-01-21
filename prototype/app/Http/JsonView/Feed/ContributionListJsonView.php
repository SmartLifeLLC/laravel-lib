<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 23:38
 */

namespace App\Http\JsonView\Feed;


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

		$feeds = $this->data->getFeeds();
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
		foreach($feeds as $feed){
			$imageUrls = $this->getImageURLs($feed['image_id_0'],$feed['image_id_1'],$feed['image_id_2'],$feed['image_id_3']);
			$displayData =
				["review_post"=>
					[
						'id' => $feed['feed_id'],
						'text' => $feed['content'],
						'is_consent' => $this->getFeelingBinaryValue($feed['feeling']),
						'product_item_id' => $feed['product_id'],
						'created_at' => $feed['created_at'],
						'total_reaction_count'=> (int) $feed['total_reaction_count'],
						'like_reaction_count'=> (int) $feed['like_reaction_count'],
						'interest_reaction_count'=> (int) $feed['interest_reaction_count'],
						'have_reaction_count'=> (int) $feed['have_reaction_count'],
						'comment_num' => (int) $feed['comment_count'],
						'image_urls' => $imageUrls,
						'review_post_id' => $feed['id'],
						'is_like' => $this->getBinaryValue($feed['feed_like_reaction_id']),
						'is_interest' => $this->getBinaryValue($feed['feed_interest_reaction_id']),
						'is_having' => $this->getBinaryValue($feed['feed_have_reaction_id']),
						"is_review_posted" => $this->getBinaryValue($feed['my_feed_id']),
						'post_user_info' =>
							[
								'user_id' => $feed['user_id'],
								'user_name' => $feed['user_name'],
								'profile_image_url' => $this->getImageURLForS3Key($feed['profile_image_s3_key']),
								'cover_image_url' => $this->getImageURLForS3Key($feed['cover_image_s3_key']),
								'introduction' => $feed['description'],
								'gender' => $this->getGenderString($feed['gender'],$feed['gender_published_flag']),
								'birthday' => $this->getBirthdayString($feed['birthday'],$feed['birthday_published_flag']),
								"is_following" => $this->getBinaryValue($feed['follow_id'])
							]
					],
				"product_item" =>
					[
						"shops" => [],
						"id" => $feed['product_id'],
						"name" => $feed['display_name'],
						"text" => "",
						"price" => $feed['price'],
						"review_post_num" => $feed['feed_count'],
						"consent_num" => $feed['positive_count'],
						"refusal_num" => $feed['negative_count'],
						"categories" => $productsCategories[$feed['product_id']],
					]
				];
			$body[] = $displayData;
		}
		$this->body = $body;
	}

}