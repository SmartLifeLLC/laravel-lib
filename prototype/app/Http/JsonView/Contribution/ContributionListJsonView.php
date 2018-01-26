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
				["contribution"=>
					[
						'id' => $contribution['id'],
						'text' => $contribution['content'],
						'is_consent' => $this->getFeelingBinaryValue($contribution['feeling']),
						'created_at' => $contribution['contribution_created_at'],
						'total_reaction_count'=> (int) $contribution['total_reaction_count'],
						'like_reaction_count'=> (int) $contribution['like_reaction_count'],
						'interest_reaction_count'=> (int) $contribution['interest_reaction_count'],
						'comment_num' => (int) $contribution['comment_count'],
						'image_urls' => $imageUrls,
						'is_like' => $this->getBinaryValue($contribution['contribution_like_reaction_id']),
						'is_interest' => $this->getBinaryValue($contribution['contribution_interest_reaction_id']),
						'is_having' => $this->getBinaryValue($contribution['contribution_have_reaction_id']),
						"is_contributed" => $this->getBinaryValue($contribution['my_contribution_id']),
						'user' =>
							[
								'id' => $contribution['user_id'],
								'user_name' => $contribution['user_name'],
								'profile_image_url' => $this->getImageURLForS3Key($contribution['profile_image_s3_key']),
								"is_following" => $this->getBinaryValue($contribution['follow_id'])
							]
					],
				"product_item" =>
					[
						"id" => $contribution['product_id'],
						"name" => $contribution['display_name'],
						"price" => (int) $contribution['price'],
						"contribution_count" => $contribution['contribution_count'],
						"positive_count" => $contribution['positive_count'],
						"negative_count" => $contribution['negative_count'],
						"categories" => array_values($productsCategories[$contribution['product_id']]),
						"image_url" => $this->getImageURLForS3Key($contribution['product_image_s3key']),
						"shops" => []
					]
				];
			$body[] = $displayData;
		}
		$this->body = $body;
	}

}