<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/12
 * Time: 2:38
 */

namespace App\Services;


use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Models\JicfsCategory;
use App\Models\JicfsProduct;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductsProductCategory;
use App\ValueObject\JicfsObject\JANProductBaseInfoVO;
use App\ValueObject\JicfsObject\JicfsManufacturerInfoVO;
use \Exception;

class ProductService extends BaseService
{
    public function createProductAndJicfsProduct(JANProductBaseInfoVO $janProductBaseInfoVO){
        $this->executeTasks(
            function() use ($janProductBaseInfoVO){
                $name = $janProductBaseInfoVO->_14_productNameKanji;
                $janCode = $janProductBaseInfoVO->_4_janCode;
                $jicfsCategoryCode = $janProductBaseInfoVO->_21_categoryCode;
                $price = $janProductBaseInfoVO->_30_price;

                //Check product exists
                $jicfsProductModel = new JicfsProduct();

                $jicfsProduct = $jicfsProductModel->getProduct($janCode,$name);
                if($jicfsProduct != null){
                    return ServiceResult::withResult($jicfsProduct['id']);
                }

                //Get category id for jicfs category code
                $jicfsCategoryModel = new JicfsCategory();
                $jicfsCategoryInfo = $jicfsCategoryModel->getCategoryInfo($jicfsCategoryCode);

                if(empty($jicfsCategoryInfo))
                    throw new \Exception("Can't find category from category code {$jicfsCategoryCode}");

                $productCategoryId = $jicfsCategoryInfo['product_category_id'];
                $productBrandId = $janProductBaseInfoVO->_9_representativeManufacturerCode;

                //Create jicfs product style



                //Create product
                $productModel = new Product();
                $productId = $productModel->create($name,$productBrandId,$price);

                //Create category
                $productsProductCategory = new ProductsProductCategory();
                $productsProductCategory->create($productId,$productCategoryId);

                $jicfsProductId = $jicfsProductModel->create($janCode,$name,$productId,$jicfsCategoryInfo['id'],$productCategoryId,$productBrandId);

                if(empty($jicfsProductId)){
                    throw new \Exception("Failed to create jicfs product");
                }
                return ServiceResult::withResult($jicfsProductId);

            },true);
    }

    /**
     * @param JicfsManufacturerInfoVO $jicfsManufacturerInfoVO
     */
    public function createProductBrand(JicfsManufacturerInfoVO $jicfsManufacturerInfoVO){
        $this->executeTasks(function() use ($jicfsManufacturerInfoVO){
            $id = $jicfsManufacturerInfoVO->_6_representativeManufacturerCode;
            $brandName = $jicfsManufacturerInfoVO->_7_companyName;
            $brandModel = new ProductBrand();
            $brand = $brandModel->getBrand($id);
            //create
            if(empty($brand)){
                $brandModel->create($id,$brandName);
            }else{
                //Check data
                if($brand['name']!=$brandName){
                    throw new Exception("Brand name does not matched {$brandName} and ".$brand['name']);
                }
            }
            return ServiceResult::withResult($id);
        },true);
    }
}