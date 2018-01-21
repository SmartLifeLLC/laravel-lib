<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/09
 * Time: 20:43
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
use Illuminate\Database\Eloquent\Model;
use DB;
class ProductCategory extends DBModel
{
    /**
     * @param $categoryName
     * @param int $ancestorId
     * @return int created category id
     */
    public function appendNewCategory($categoryName,$ancestorId = 0){
        if ($ancestorId == 0) {
            $depth = 0;
            $ancestorUniqueCategoryName = null;
        }else{
            $categoryDetail = $this->getCategoryDetail($ancestorId);
            $depth = $categoryDetail->depth + 1;
            $ancestorUniqueCategoryName = $categoryDetail->unique_name;
        }

        $uniqueName = $this->getUniqueCategoryName($categoryName,$ancestorUniqueCategoryName);

        //Check unique name exist (means check category exists)
        $result = self::where('unique_name',$uniqueName)->first();
        if($depth == 2 && !empty($result)){
            echo ($result->unique_name ."\r\n");
        }

        if(!empty($result))  return $result->id;


        $date = date(DateTimeFormat::General);
        //Create new category
        $id = self::insertGetId(
              [
                'name'=>$categoryName,
                'unique_name'=>$uniqueName,
                'created_at' => $date,
                'updated_at' => $date
                ]
        );

        //Create hierarchy
        //DB::insert()
        $insertQuery =
        " INSERT INTO  product_category_hierarchies (ancestor_id,descendant_id,depth) ".
        " SELECT ancestor_id,{$id},depth + 1 FROM product_category_hierarchies WHERE descendant_id = {$ancestorId}".
        " UNION ALL SELECT {$id},{$id},{$depth}";
        DB::insert($insertQuery);
        return $id;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getCategoryDetail($id){
        $result =
            self::select([
                    'product_categories.id',
                     'product_categories.name',
                     'unique_name',
                     'product_count',
                     'product_category_hierarchies.depth'])
            ->join('product_category_hierarchies',function($join){
                $join->on('product_category_hierarchies.descendant_id','=','product_categories.id');
                $join->on('product_category_hierarchies.ancestor_id','=','product_categories.id');
            })->where('product_categories.id',$id)
                ->first();


        return $result;
    }

    /**
     * @param $currentCategoryName
     * @param null $ancestorCategoryName
     * @return string
     */
    private function getUniqueCategoryName($currentCategoryName,$ancestorCategoryName = null){
        if($ancestorCategoryName == null)
            return $currentCategoryName;

        return $ancestorCategoryName.' / '. $currentCategoryName ;
    }


    /**
     * @param $categoryId
     */
    public function increaseProductCount($categoryId){
        $set = "product_count = product_count + 1";
        $this->_updateCount($categoryId,$set);
    }

    /**
     * @param $categoryId
     */
    public function decreaseFeedCount($categoryId){
        $set = "feed_count = feed_count - 1";
        $this->_updateCount($categoryId,$set);
    }


    /**
     * @param $categoryId
     * @param $set
     */
    private function _updateCount($categoryId,$set){
        $updateQuery =
            "UPDATE product_categories
              SET {$set}
              WHERE id IN (
                  SELECT ancestor_id
                  FROM product_category_hierarchies
                  WHERE descendant_id = ?);
            ";
        DB::update($updateQuery,[$categoryId]);
    }


    /**
     * @param $ancestorId
     * @param $depth
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getListAtDepth($ancestorId, $depth){
        $queryBuilder = self::select([
            'product_categories.id',
            'product_categories.name',
            'product_category_feed_counts.feed_count',
            'product_category_feed_counts.positive_count',
            'product_category_feed_counts.negative_count',
            'unique_name'])
            ->join('product_category_hierarchies',function($join){
                $join->on('product_category_hierarchies.descendant_id','=','product_categories.id');
            })
            ->leftJoin('product_category_feed_counts','product_category_feed_counts.product_category_id','=','product_categories.id')
            ;
            if($ancestorId > 0){
                $queryBuilder = $queryBuilder->where('product_category_hierarchies.ancestor_id',$ancestorId);
            }

            $queryBuilder =
                $queryBuilder
                    ->where('product_category_hierarchies.depth',$depth)
                    //->orderBy('product_category_feed_counts.feed_count','desc');
                    ->orderByRaw('CAST(product_categories.name AS CHAR) asc');
            return $queryBuilder->get();
    }

    /**
     * @param $categoryId
     * @return mixed
     */
    public function getProductsCount($categoryId){
        $result = $this->find($categoryId);
        return $result['product_count'];
    }

	/**
	 * @param array $descendantIds
	 * @return array
	 */

	public function getAncestorIdList(Array $descendantIds){
		$productCategories = self::select([
			'product_categories.id'])
			->join('product_category_hierarchies',function($join){
				$join->on('product_category_hierarchies.ancestor_id','=','product_categories.id');
			})->whereIn('product_category_hierarchies.descendant_id',$descendantIds)
			->groupBy('product_categories.id')->get();

		$ids = [];
		foreach ($productCategories as $productCategory){
			$ids [] = $productCategory->id;
		}
		return $ids;
	}

}