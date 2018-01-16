<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//From here added by Jung
//ユーザ新規コントローラ

//New Apis
Route::group(
    ['prefix' => 'user',
    'namespace'=> 'User'],
    function () {
        //Get ID and Auth
        Route::get('/auth/{facebookId}', 'AuthController@getUserAuth');

        //Get user notification logs
        Route::get('/notificationLogs/{boundaryId?}/{limit?}/{orderTypeString?}','NotificationLogController@getLogs');

        //Switch user block status.
        //isBlockOn : {0/1}
        Route::put('/block/{targetUserId}/{isBlockOn}','BlockController@switchUserBlockStatus');

        //Switch user Follow status
        Route::put('/follow/{targetUserId}/{isFollowOn}','FollowController@switchUserFollowStatus');

        Route::post('/device/register','DeviceController@register');

        //User detail
        Route::get('/info','UserController@getInfo');
        //Old version
        Route::post('/get','UserController@getInfo');

        //User setting
        Route::get('/setting/notification/list','UserController@getNotificationSettings');
        //Old
        Route::get('/setting/show/{user_id?}'   ,'UserController@getNotificationSettings');

    });

Route::group(['prefix'=>'featured','namespace'=>'Featured'],
    function(){
        Route::get('/users/onInit','FeaturedUsersController@getListOnAppInit');
    });

Route::group(['prefix'=>'category'],
    function(){
        Route::get('list/{ancestorId?}','CategoryController@getList');
    });

Route::group(['prefix'=>'product'], function(){
    Route::get('list','ProductController@getList');
});

Route::group(['prefix'=>'feed','namespace'=>'Feed'],function(){
	Route::post('contribution/create','ContributionController@create');
});


//Old apis
Route::group(['prefix' => 'block'], function(){
    Route::post('/user', 'User\BlockController@blockUser');
    Route::post('/cancel', 'User\BlockController@cancelBlock');
});

// フォロー0
Route::group(['prefix' => 'follow'], function () {
    Route::post('/user', 'User\FollowController@followUser');
    Route::post('/cancel', 'User\FollowController@followCancel');
});

// レコメンド
Route::group(['prefix' => 'recommend_users'], function () {
    Route::post('/', 'Featured\FeaturedUsersController@getListOnAppInit');
});


//// 検索 - category controller および product controllerで統一
//Route::group(['prefix' => 'search'], function () {
//    Route::get('/', 'SearchController@index'); //Done
//    Route::get('/category/large', 'SearchController@searchForLargeCategory'); //Done - Category::getList
//    Route::get('/category/medium/{section_id}', 'SearchController@searchForMediumCategory'); // Done - Category::getList
//    Route::get('/category/get/{category_id}/{offset?}/{limit?}', 'SearchController@getFromCategoryId'); //Done
//});
//


//// 商品
//Route::group(['prefix' => 'product_item'], function () {
//    Route::get('/jan_code', 'ProductItemController@jan_code'); // Done - Product::getList
//    Route::get('/{product_item_id}', 'ProductItemController@get')->where('product_item_id', '[0-9]+');
//    Route::post('/{product_item_id}/{offset?}/{limit?}', 'ProductItemController@_tmp_get')->where('product_item_id', '[0-9]+');
//    Route::post('/consent/{product_item_id}/{offset?}/{limit?}', 'ProductItemController@getConsentList');
//    Route::post('/refusal/{product_item_id}/{offset?}/{limit?}', 'ProductItemController@getRefusalList');
//});
//


//// ユーザー
//Route::group(['prefix' => 'user'], function () {

//    Route::post('/get', 'LoginUserController@get'); => OK
//    Route::post('/regist', 'UserController@regist'); => 廃止
//    Route::get('/setting/show/{user_id}', 'UserSettingController@show'); => done

//    Route::post('/edit', 'UserController@edit');
//    Route::get('/setting/show/block/{user_id}/{offset?}', 'UserSettingController@blockList');
//    Route::post('/setting', 'UserSettingController@update');
//});


//
//
//
//





//// 投稿
//Route::group(['prefix' => 'post'], function () {
//    Route::post('/product_item_review', 'PostController@createProductItemReviewPost');
//    Route::post('/product_item_review_with_having_reaction', 'PostController@createProductItemReviewPostWithHavingReaction');
//    Route::post('/comment', "PostController@createCommentToReviewPost");
//    Route::post('/delete_comment', "PostController@deleteCommentToReviewPost");
//});
//


//
//
//
//Route::group(['prefix' => 'page'], function(){
//    Route::post('/', 'UserPageController@myPage');
//    Route::post('/user', 'UserPageController@userPage');
//    Route::post('/user/review/{offset?}/{limit?}', 'UserPageController@reviewListUser');
//    Route::post('/user/following/{offset?}/{limit?}', 'UserPageController@userFollowing');
//    Route::post('/user/followers/{offset?}/{limit?}', 'UserPageController@userFollowers');
//    Route::post('/user/interest/{offset?}/{limit?}', 'UserPageController@userInterest');
//    Route::post('/following/{offset?}/{limit?}', 'UserPageController@following');
//    Route::post('/followers/{offset?}/{limit?}', 'UserPageController@followers');
//    Route::post('/review/{offset?}/{limit?}', 'UserPageController@review');
//    Route::post('/interest/{offset?}/{limit?}', 'UserPageController@interest');
//});
//

//

//// タイムライン
//Route::group(['prefix' => 'timeline'], function () {
//    Route::post('/my/{offset?}/{limit?}', 'TimelineController@getMyTimeLine');
//});
//
//// リアクション
//Route::group(['prefix' => 'reaction'], function () {
//    Route::post('/review_post/all/{offset?}/{limit?}', 'ReactionController@getReactionListForAll');
//    Route::post('/review_post/like/{offset?}/{limit?}', 'ReactionController@getReactionListForLike');
//    Route::post('/review_post/interest/{offset?}/{limit?}', 'ReactionController@getReactionListForInterest');
//    Route::post('/review_post/having/{offset?}/{limit?}', 'ReactionController@getReactionListForHaving');
//    Route::post('/review_post/add', 'ReactionController@addToReviewPost');
//    Route::post('/review_post/cancel', 'ReactionController@cancelToReviewPost');
//});
//

//
//// レビュー投稿
//Route::group(['prefix' => 'review_post'], function () {
//    Route::post('/check', 'PostController@isReviewPosted');
//    Route::post('/edit', 'PostController@edit');
//    Route::post('/delete', 'PostController@delete');
//    Route::post('/{reviewPostId}', 'PostController@getReviewPostDetail');
//});
//


//

//Route::group(['prefix'=> 'feeds'],function(){
//    Route::get('/detail/{feedId}','FeedsController@detail');
//    Route::get('/comments/{feedId}/{boundaryId}','FeedsController@comments');
//});