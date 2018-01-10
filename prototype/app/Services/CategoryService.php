<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/09
 * Time: 20:07
 */

namespace App\Services;


class CategoryService extends BaseService
{
    public function generateCategoriesFromRawData(){

        $this->executeTasks(call_user_func('generateCategories'),true);
    }


    private function generateCategories(){

    }
}