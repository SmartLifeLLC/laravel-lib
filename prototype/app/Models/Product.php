<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/12
 * Time: 3:50
 */

namespace App\Models;


use App\Constants\DefaultValues;
use App\Lib\Util;
use App\ValueObject\ProductAndCountDataVO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
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
            'search_text' => $searchText
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
    public function getProductAndCountByKeyword($keyword, $page = 0, $limit = DefaultValues::QUERY_DEFAULT_LIMIT):ProductAndCountDataVO{
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
                ->select(['display_name','products.feed_count','products.positive_feed_count','products.negative_feed_count','product_categories.unique_name as breadcrumb','images.s3_key'])
                    ->leftJoin('products_product_categories','products.id','=','products_product_categories.product_id')
                    ->leftJoin('product_categories','products_product_categories.product_category_id','=','product_categories.id')
                    ->leftJoin('images','images.id','=','products.image_id')
                    ->whereRaw($where)
                ->orderBy('products.feed_count','desc')
                ->limit($limit)
                ->offset($page*$limit)
                ->get();
        return new ProductAndCountDataVO($countResult['count'],$products->toArray());
    }
}