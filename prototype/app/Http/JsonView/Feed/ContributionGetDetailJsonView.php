<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/21
 * Time: 5:51
 */

namespace App\Http\JsonView\Feed;


use App\Http\JsonView\JsonResponseView;
use App\ValueObject\ContributionGetDetailResultVO;

class ContributionGetDetailJsonView extends JsonResponseView
{
	/**
	 * @var ContributionGetDetailResultVO
	 */
	protected $data;
	function createBody()
	{
		$feed = $this->data->getFeed();
		$product = $this->data->getProduct();
		$categories = $this->data->getProductCategories();
		$commentNum = $this->data->getCommentCount();
		$imageUrls = $this->getImageURLs($feed['image_id_0'],$feed['image_id_1'],$feed['image_id_2'],$feed['image_id_3']);
		$body =
			[
				"review_post" =>
					[
						'id' => $feed['feed_id'],
						'text' => $feed['content'],
						'is_consent' => $this->getFeelingBinaryValue($feed['feeling']),
						'product_item_id' => $product['id'],
						'created_at' => $feed['created_at'],
						'comment_num' => $commentNum,
						'image_urls' => $imageUrls,
						'post_user_info' =>
							[
								'user_id' => $feed['user_id'],
								'user_name' => $feed['user_name'],
								'profile_image_url' => $this->getImageURLForS3Key($feed['profile_image_s3_key']),
								'cover_image_url' => $this->getImageURLForS3Key($feed['cover_image_s3_key']),
								'introduction' => $feed['description'],
								'gender' => $this->getGenderString($feed['gender'],$feed['gender_published_flag']),
								'birthday' => $this->getBirthdayString($feed['birthday'],$feed['birthday_published_flag']),
							]
					],
				"product_item" =>
					[
						"shops" => [],
						"id" => $product['id'],
						"name" => $product['display_name'],
						"text" => "",
						"review_post_num" => $product['feed_count'],
						"consent_num" => $product['positive_count'],
						"refusal_num" => $product['negative_count'],
						"categories" => $categories,
					],
				"options" =>
					[
						"review_post_reaction" =>
							[
								'review_post_id' => $feed['id'],
								'is_like' => $this->getBinaryValue($feed['feed_like_reaction_id']),
								'is_interest' => $this->getBinaryValue($feed['feed_interest_reaction_id']),
								'is_having' => $this->getBinaryValue($feed['feed_have_reaction_id'])
							],
						"is_review_posted" => $this->getBinaryValue($feed['my_feed_id']),
						"is_following" => $this->getBinaryValue($feed['follow_id'])
					]
			];
		$this->body = $body;
	}
}