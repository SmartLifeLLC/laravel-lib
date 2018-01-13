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
class ProductCategory extends Model
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
    public function decreaseProductCount($categoryId){
        $set = "product_count = product_count - 1";
        $this->_updateCount($categoryId,$set);
    }

    /**
     * @param $categoryId
     */
    public function increaseFeedCount($categoryId){
        $set = "feed_count = feed_count + 1";
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
}