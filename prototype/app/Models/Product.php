<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/12
 * Time: 3:50
 */

namespace App\Models;


use App\Constants\DateTimeFormat;
use App\Constants\DefaultValues;
use App\Constants\QueryOrderTypes;
use App\Lib\Util;
use App\ValueObject\ProductAndCountDataVO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends DBModel
{
    public $timestamps = false;
    /**
     * @param $name
     * @param $productManufacturerId
     * @param int $price
     * @param int $imageId
     * @param Date $releaseDate
     * @return mixed
     *
     */
    public function createGetId($name, $productManufacturerId, $price = 0 , $imageId = 0, $releaseDate = null){
        $searchText = Util::getNGram($name,DefaultValues::PRODUCT_N_GRAM_SIZE);
        $data = [
            'name' => $name,
            'product_manufacturer_id' => $productManufacturerId,
            'price' => $price,
            'image_id' => $imageId,
            'display_name' => $name,
            'search_text' => $searchText,
	        'created_date' => date(DateTimeFormat::DateShort)
        ];

        if($releaseDate != null)
            $data['release_date'] = $releaseDate;

        return $this->insertGetId($data);
    }

    /**
     * @param $productId
     * @param $displayName
     * @param $price
     * @param $brandId
     * @param $imageId
     * @throws \Exception
     */
    public function updateScrapingData($productId,$displayName, $price, $brandId, $imageId){
        $product = $this->find($productId);
        if($product == null){
            throw new \Exception("Failed to find product for id ".$productId);
        }

        if(!empty($displayName)){
            $product->display_name = $displayName;
        }

        if(!empty($price)){
            $product->price = $price;
        }

        if(!empty($brandId)){
            $product->product_brand_id = $brandId;
        }

        if(!empty($imageId)){
            $product->image_id = $imageId;
        }
        $product->save();
    }


    /**
     * @param $productName
     * @return Model|null|static
     */
    public function getProductByName($productName){
        return $this->where('name',$productName)->first();
    }

    /**
     * Todo : productのカテゴリが複数あった場合の改善
     * Productのカテゴリが複数の場合はlimitが正しく動作しない。
     * 改善が必要
     * aws elastic searchをつかうには、企画を満たすことが厳しいうえに
     * 検索対象が商品タイトルのみであれば Full Text Indexの活用で十分対応できる。
     * @param $keyword
     * @param int $page
     * @param int $limit
     * @return ProductAndCountDataVO
     */
    public function getProductsAndCountByKeyword($keyword,$orderList, $limit = DefaultValues::QUERY_DEFAULT_LIMIT, $page = DefaultValues::QUERY_DEFAULT_PAGE ):ProductAndCountDataVO{
        $nGramKeyword = Util::getNGram($keyword,DefaultValues::PRODUCT_N_GRAM_SIZE);
        $where = "match(`search_text`) against('+{$nGramKeyword}' IN BOOLEAN MODE)";
        $countResult =
            $this->selectRaw('count(id) as count')
            ->whereRaw($where)
            ->first();
        $count = ($countResult['count']);
        if($count == 0){
            return new ProductAndCountDataVO(0,[]);
        }

        $products =
            $this
                ->getQueryBuilderForProducts($orderList,$limit,$page)
                ->whereRaw($where)
                ->get();

        return new ProductAndCountDataVO($countResult['count'],$products->toArray());
    }


    /**
     * category total countは　product_categoryテーブルで簡単に取得できるのでそちらを利用
     * @param $categoryId
     * @param int $totalCount
     * @param int $page
     * @param int $limit
     * @return ProductAndCountDataVO
     */
    public function getProductsByCategoryId($categoryId,$orderList,$totalCount = 0,$limit = DefaultValues::QUERY_DEFAULT_LIMIT, $page = DefaultValues::QUERY_DEFAULT_PAGE ){

        if($totalCount == 0){
            return new ProductAndCountDataVO($totalCount,[]);
        }
        $products =
            $this->getQueryBuilderForProducts($orderList,$limit,$page)
            ->whereIn('products_product_categories.product_category_id',function($query) use ($categoryId){
               $query
                   ->select('descendant_id')
                   ->from(with(new ProductCategoryHierarchy())->getTable())
                   ->where('ancestor_id',$categoryId);})
            ->get();
        return new ProductAndCountDataVO($totalCount,$products->toArray());
    }

	/**
	 * @param $janCode
	 * @param $orderList
	 * @return ProductAndCountDataVO
	 */
    public function getProductsByJanCode($janCode,$orderList){
        $products =
            $this->getQueryBuilderForProducts($orderList, DefaultValues::QUERY_DEFAULT_LIMIT_FOR_JANCODE,DefaultValues::QUERY_DEFAULT_PAGE)
            ->leftJoin('jicfs_products','jicfs_products.product_id','=','products.id')
            ->where('jicfs_products.jan_code',$janCode)
            ->get();

        $resultArray = $products->toArray();
        return new ProductAndCountDataVO(count($resultArray),$resultArray);
    }

	/**
	 * @param $orderList
	 * @param $limit
	 * @param $page
	 * @return mixed
	 */
    private function getQueryBuilderForProducts($orderList, $limit , $page ){
            if($page < 1) $page = 1;
            $queryBuilder =
	            $this
                ->select([
                    'display_name',
                    'product_categories.unique_name as breadcrumb',
                    'images.s3_key',
                    'products.price',
                    'product_contribution_counts.contribution_count',
                    'product_contribution_counts.positive_count',
                    'product_contribution_counts.negative_count'])
                ->leftJoin('products_product_categories','products.id','=','products_product_categories.product_id')
                ->leftJoin('product_contribution_counts','products.id','=','product_contribution_counts.product_id')
                ->leftJoin('product_categories','products_product_categories.product_category_id','=','product_categories.id')
                ->leftJoin('images','images.id','=','products.image_id')
                ->offset($this->getOffset($limit,$page))
                ->limit($limit);

            if(!empty($orderList)){
            	foreach ($orderList as $order){
            		$fieldAndOrder = explode('-',$order);
            		if(count($fieldAndOrder) < 2)  continue;
					if(!in_array($fieldAndOrder[0],QueryOrderTypes::PRODUCT_ORDER_LIST))  continue;
					if($fieldAndOrder[1] != QueryOrderTypes::DESCENDING && $fieldAndOrder[1]  != QueryOrderTypes::ASCENDING)  continue;
		            $queryBuilder = $queryBuilder->orderBy($fieldAndOrder[0],$fieldAndOrder[1]);
	            }
            }
            return $queryBuilder;
    }

	/**
	 * @param $productId
	 * @return mixed
	 */
    public function getProductDetail($productId){
    	return
		    $this
			    ->where('products.id',$productId)
		        ->leftJoin('product_contribution_counts','product_contribution_counts.product_id','=','products.id')
		        ->first();

    }


}