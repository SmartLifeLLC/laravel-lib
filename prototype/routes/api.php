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

Route::group(
    ['prefix' => 'users',
    'namespace'=> 'User'],
    function () {
        Route::get('/auth/{facebookId}', 'AuthController@getIdAndAuth');
});
//
//
//
//
//// 検索
//Route::group(['prefix' => 'search'], function () {
//    Route::get('/', 'SearchController@index');
//    Route::get('/category/large', 'SearchController@searchForLargeCategory');
//    Route::get('/category/medium/{section_id}', 'SearchController@searchForMediumCategory');
//    Route::get('/category/get/{category_id}/{offset?}/{limit?}', 'SearchController@getFromCategoryId');
////    Route::get('/category/get/{section_id}/{offset?}/{limit?}', 'SearchController@getFromCategoryId');
//});
//
//// 商品
//Route::group(['prefix' => 'product_item'], function () {
//    Route::get('/jan_code', 'ProductItemController@jan_code');
//    Route::get('/{product_item_id}', 'ProductItemController@get')->where('product_item_id', '[0-9]+');
//    Route::post('/{product_item_id}/{offset?}/{limit?}', 'ProductItemController@_tmp_get')->where('product_item_id', '[0-9]+');
//    Route::post('/consent/{product_item_id}/{offset?}/{limit?}', 'ProductItemController@getConsentList');
//    Route::post('/refusal/{product_item_id}/{offset?}/{limit?}', 'ProductItemController@getRefusalList');
//});
//
//// 投稿
//Route::group(['prefix' => 'post'], function () {
//    Route::post('/product_item_review', 'PostController@createProductItemReviewPost');
//    Route::post('/product_item_review_with_having_reaction', 'PostController@createProductItemReviewPostWithHavingReaction');
//    Route::post('/comment', "PostController@createCommentToReviewPost");
//    Route::post('/delete_comment', "PostController@deleteCommentToReviewPost");
//});
//
//// ユーザー
//Route::group(['prefix' => 'UserVO'], function () {
//    Route::post('/get', 'LoginUserController@get');
//    Route::post('/regist', 'UserController@regist');
//    Route::post('/edit', 'UserController@edit');
//    Route::get('/setting/show/{user_id}', 'UserSettingController@show');
//    Route::get('/setting/show/block/{user_id}/{offset?}', 'UserSettingController@blockList');
//    Route::post('/setting', 'UserSettingController@update');
//});
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
//// フォロー
//Route::group(['prefix' => 'follow'], function () {
//    Route::post('/user', 'FollowController@followUser');
//    Route::post('/cancel', 'FollowController@followCancel');
//});
//
//// ブロック
//Route::group(['prefix' => 'block'], function(){
//    Route::post('/user', 'BlockController@blockUser');
//    Route::post('/cancel', 'BlockController@cancelBlock');
//});
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
//// ログイン
//Route::group(['prefix' => 'login'], function () {
//    Route::post('/get_fb_token', 'LoginController@getFBLoginToken');
//    Route::post('/do_fb_login', 'LoginController@processFBLogin');
//});
//
//// レビュー投稿
//Route::group(['prefix' => 'review_post'], function () {
//    Route::post('/check', 'PostController@isReviewPosted');
//    Route::post('/edit', 'PostController@edit');
//    Route::post('/delete', 'PostController@delete');
//    Route::post('/{reviewPostId}', 'PostController@getReviewPostDetail');
//});
//
//// 通知
//Route::group(['prefix' => 'notification'], function () {
//    Route::post('/regist_device', 'NotificationDeviceTokenController@registFCMDeviceToken');
//    Route::post('/user_logs/{offset?}/{limit?}', 'NotificationLogsController@listUserNotificationLogs');
//    Route::post('/all_already_read', 'NotificationLogsController@updateAllUserLogsToAlreadyRead');
//});
//
//// レコメンド
//Route::group(['prefix' => 'recommend_users'], function () {
//    Route::post('/', 'RecommendUserController@get');
//});
//
////From here added by Jung
////ユーザ新規コントローラ
//Route::group(['prefix' => 'users'], function () {
//    Route::get('/auth/{facebookId}', 'UsersController@auth');
//});
//
//Route::group(['prefix'=> 'feeds'],function(){
//    Route::get('/detail/{feedId}','FeedsController@detail');
//    Route::get('/comments/{feedId}/{boundaryId}','FeedsController@comments');
//});