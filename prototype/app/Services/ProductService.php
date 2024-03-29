<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/12
 * Time: 2:38
 */

namespace App\Services;


use App\Constants\DefaultValues;
use App\Constants\StatusCode;
use App\Lib\JSYService\ServiceResult;
use App\Lib\Util;
use App\Models\JicfsCategory;
use App\Models\JicfsManufacturer;
use App\Models\JicfsProduct;
use App\Models\JicfsScraping;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductsProductCategory;
use App\Services\Tasks\CreateGetProductIdTask;
use App\ValueObject\CreateJicfsProductResultVO;
use App\ValueObject\CreateProductManufacturerResultVO;
use App\ValueObject\JicfsObject\JANProductBaseInfoVO;
use App\ValueObject\JicfsObject\JicfsManufacturerInfoVO;
use App\ValueObject\ProductAndCountDataVO;
use DateTime;
use \Exception;

class ProductService extends BaseService
{
    /**
     * @param JANProductBaseInfoVO $janProductBaseInfoVO
     * @return ServiceResult
     */
    public function createProductAndJicfsProduct(JANProductBaseInfoVO $janProductBaseInfoVO):ServiceResult{
        return $this->executeTasks(
            function() use ($janProductBaseInfoVO){


                $jicfsProductName = $janProductBaseInfoVO->_14_productNameKanji;
                $janCode = $janProductBaseInfoVO->_4_janCode;
                $jicfsCategoryCode = $janProductBaseInfoVO->_21_categoryCode;
                $price = $janProductBaseInfoVO->_30_price;
                $voucherProductNameKanji = $janProductBaseInfoVO->_18_voucherProductNameKanji;
                $displayUnit = $janProductBaseInfoVO->_16_displayUnitKanji;
                $totalQuantity = (int) $janProductBaseInfoVO->_31_totalQuantity;
                $quantityUnitCode = (int) $janProductBaseInfoVO->_32_quantityUnitCode;
                $productManufacturerId = $janProductBaseInfoVO->_9_representativeManufacturerCode;

                //productの名前はjicfsのproduct名がない場合があるので
                //その際には伝票表示名前を使う
                //ユーザに表示する時には amazonでクローリングした内容を先に見せる
                $productName = (empty($jicfsProductName))?$voucherProductNameKanji:$jicfsProductName;

                $formattedReleaseDate = DateTime::createFromFormat('YMD', $janProductBaseInfoVO->_44_releaseDate);
                if($formattedReleaseDate === false){
                    $releaseDate =  null;
                } else {
                    $releaseDate = $formattedReleaseDate->format(Util::getDateFormat());
                }


                //Check jicfs product exists
                $jicfsProductModel = new JicfsProduct();
                //jicfs product nameは　ない場合があるので jicfsの商品のユニーク性確認は voucher nameで行う.
                $jicfsProduct = $jicfsProductModel->getProduct($janCode,$voucherProductNameKanji);
                if($jicfsProduct != null){
                    $createProductResult =
                        new CreateJicfsProductResultVO(
                        false,
                        false,
                        $jicfsProduct['id'],
                        $jicfsProduct['product_id']);

                    return ServiceResult::withResult($createProductResult);
                }

                //Get category id for jicfs category code
                    $jicfsCategoryInfo = (new JicfsCategory())->getCategoryInfo($jicfsCategoryCode);
                if(empty($jicfsCategoryInfo))
                    throw new \Exception("Can't find category from category code {$jicfsCategoryCode}");
                $productCategoryId = $jicfsCategoryInfo['product_category_id'];

                //Get product id
                $createGetProductIdTask = new CreateGetProductIdTask($productName,$productManufacturerId,$price,$releaseDate,$productCategoryId);
                $createGetProductIdTask->run();
                $createProductResult = $createGetProductIdTask->getResult();

                //Create jicfs product model
                $jicfsProductId = $jicfsProductModel->createGetId(
                    $janCode,
                    $jicfsProductName,
                    $createProductResult->getProductId(),
                    $jicfsCategoryInfo['id'],
                    $productCategoryId,
                    $productManufacturerId,
                    $displayUnit,
                    $voucherProductNameKanji,
                    $totalQuantity,
                    $quantityUnitCode,
                    $releaseDate
                    );

                //create jicfs scraping model
                (new JicfsScraping())->createGetId($jicfsProductId);

                if(empty($jicfsProductId)){
                    throw new \Exception("Failed to create jicfs product");
                }

                $createJicfsProductResult =
                    new CreateJicfsProductResultVO(
                        true,
                        $createProductResult->isProductCreated(),
                        $jicfsProductId,
                        $createProductResult->getProductId());
                return ServiceResult::withResult($createJicfsProductResult);

            },true);
    }

    /**
     * @param JicfsManufacturerInfoVO $jicfsManufacturerInfoVO
     * @return ServiceResult
     */
    public function createProductManufacturer(JicfsManufacturerInfoVO $jicfsManufacturerInfoVO):ServiceResult{
        return $this->executeTasks(function() use ($jicfsManufacturerInfoVO){
            $id = $jicfsManufacturerInfoVO->_6_representativeManufacturerCode;
            $name = $jicfsManufacturerInfoVO->_7_companyName;
            $manufacturerModel = new JicfsManufacturer();
            $manufacturer = $manufacturerModel->find($id);
            $isCreated = false;
            //create
            if(empty($manufacturer)){
                $isCreated = true;
                $manufacturerModel->createGetId($id,$name);
            }else{
                //Check data
                if($manufacturer['name']!=$name){
                    throw new Exception("Manufacturer name does not matched {$name} and {$id} with ".  $manufacturer['name']." and "."id ".$manufacturer['id']);
                }
            }
            return ServiceResult::withResult(new CreateProductManufacturerResultVO($id,$isCreated));
        },true);
    }

	/**
	 * @param $keyword
	 * @param $categoryId
	 * @param $limit
	 * @param $page
	 * @param $orderList
	 * @return ServiceResult
	 */
    public function getProductListByKeyword($keyword,$categoryId,$limit,$page,$orderList){
        return $this->executeTasks(function() use ($keyword,$categoryId,$limit,$page,$orderList){
	        //$keyword = base64_decode($keyword);
            $result = (new Product())->getProductsAndCountByKeyword($keyword,$orderList,$limit,$page);
	        $this->setProductCategories($result);
            return ServiceResult::withResult($result);
        });
    }

	/**
	 * @param $categoryId
	 * @param $limit
	 * @param $page
	 * @param $orderList
	 * @return ServiceResult
	 */
    public function getProductListByCategory($categoryId,$limit,$page,$orderList){
        return $this->executeTasks(function() use ($categoryId,$limit,$page,$orderList){
            $count = (new ProductCategory())->getProductsCount($categoryId);
            $result = (new Product())->getProductsByCategoryId($categoryId,$orderList,$count,$limit,$page);
            $this->setProductCategories($result);
            return ServiceResult::withResult($result);
        });
    }

	/**
	 * @param $janCode
	 * @param $orderList
	 * @return ServiceResult
	 */
    public function getProductListByJanCode($janCode,$orderList){
        return $this->executeTasks(function()use ($janCode,$orderList){
            $result = (new Product())->getProductsByJanCode($janCode,$orderList);
	        $this->setProductCategories($result);
            return ServiceResult::withResult($result);
        });
    }


	/**
	 * @param $productId
	 * @return ServiceResult
	 */
    public function getProduct($productId){
    	return $this->executeTasks(function() use ($productId){
			$result = (new Product())->getProductById($productId);
			$this->setProductCategories($result);
			return ServiceResult::withResult($result);
	    });
    }


	/**
	 * @param ProductAndCountDataVO $productAndCountDataVO
	 */
    private function setProductCategories(ProductAndCountDataVO &$productAndCountDataVO){
	    $productIds = [];
	    foreach($productAndCountDataVO->getProducts() as $product){
		    $productIds[] = $product['id'];
	    }
	    $productsCategories = (new ProductsProductCategory())->getProductsCategories($productIds);
		$productAndCountDataVO->setProductsCategories($productsCategories);
    }

}