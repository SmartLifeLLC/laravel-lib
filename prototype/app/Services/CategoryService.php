<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/09
 * Time: 20:07
 */

namespace App\Services;


use App\Lib\JSYService\ServiceResult;
use App\Models\ProductCategory;

class CategoryService extends BaseService
{
//    public function generateCategoriesFromRawData(){
//
//        $this->executeTasks(call_user_func('generateCategories'),true);
//    }
//
//
//    private function generateCategories(){
//
//    }


    /**
     * @param $ancestorId
     * @return ServiceResult
     */
    public function getList($ancestorId){
        return $this->executeTasks($this->getListTask($ancestorId),false);
    }

    /**
     * @param int $ancestorId
     * @return \Closure
     */
    private function getListTask($ancestorId = 0 ){
        return function() use ($ancestorId){
            $categoryModel = new ProductCategory();
            $depth = 0;
            if($ancestorId > 0){
                $ancestorCategory = $categoryModel->getCategoryDetail($ancestorId);
                $depth = $ancestorCategory['depth'] + 1;
            }

            $categoryList = $categoryModel->getList($ancestorId,$depth);
            $serviceResult = ServiceResult::withResult($categoryList);
            return $serviceResult;
        };
    }
}