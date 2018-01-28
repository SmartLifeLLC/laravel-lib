<?php
/**
 * class ListJsonView
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2018/01/14
 */
namespace App\Http\JsonView\Category;
use App\Http\JsonView\JsonResponseView;

class CategoryListJsonView extends JsonResponseView
{
    /**
     * @var see CategoryService::getList
     */
    protected $data;
    function createBody()
    {
    	$categories = [];
    	foreach ($this->data as $categoryData){
    		$category =
			    [
		      	    'id'=>$categoryData['id'],
		            'name'=>$categoryData['name'],
		            'contribution_count'=>(int) $categoryData['contribution_count'],
				    'positive_count'=>(int) $categoryData['positive_count'],
				    'negative_count'=>(int) $categoryData['negative_count'],
				    'breadcrumb'=>(int) $categoryData['breadcrumb']
		        ];
		    $categories[] = $category;
	    }
        $this->body = $categories;


    }
}