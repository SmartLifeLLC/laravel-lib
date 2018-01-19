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

	    //Follow and follower list
	    Route::get('/follow/list','FollowController@getFollowList');
	    Route::get('/follower/list','FollowController@getFollowerList');


        Route::post('/device/register','DeviceController@register');

        //User detail
        Route::get('/info','UserController@getInfo');
        //Old version
        Route::post('/get','UserController@getInfo');

        //User setting
        Route::get('/setting/notification/list','UserController@getNotificationSettings');
        //Old User Setting
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
	Route::post('contribution/edit','ContributionController@edit');
	Route::post('contribution/find','ContributionController@find');

	Route::post('comment/create','CommentController@create');
	Route::delete('comment/delete/{commentId}','CommentController@delete');
	Route::get('comment/list/{feedId}/{boundaryId}','CommentController@getList');

	//Route::post('/review_post/add', 'ReactionController@addToReviewPost');
	Route::post('reaction/do','ReactionController@doReaction');
	//Route::post('/review_post/cancel', 'ReactionController@cancelToReviewPost');
	Route::post('reaction/cancel','ReactionController@cancelReaction');

	Route::get('reaction/list/{feedId}','ReactionController@getList');

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



//// リアクション
//Route::group(['prefix' => 'reaction'], function () {


//    Route::post('/review_post/all/{offset?}/{limit?}', 'ReactionController@getReactionListForAll'); //Done 2018 - 01 - 20
//    Route::post('/review_post/like/{offset?}/{limit?}', 'ReactionController@getReactionListForLike'); //Done 2018 - 01 - 20
//    Route::post('/review_post/interest/{offset?}/{limit?}', 'ReactionController@getReactionListForInterest'); //Done 2018 - 01 - 20
//    Route::post('/review_post/having/{offset?}/{limit?}', 'ReactionController@getReactionListForHaving'); //No 対応 2018 - 01 - 20


//2017-01-18
//    Route::post('/review_post/add', 'ReactionController@addToReviewPost'); //Done.
//    Route::post('/review_post/cancel', 'ReactionController@cancelToReviewPost'); //Done.
//});
//

//// 投稿
//Route::group(['prefix' => 'post'], function () {
//    Route::post('/product_item_review', 'PostController@createProductItemReviewPost'); //Done

//2017-01-18
//    Route::post('/product_item_review_with_having_reaction', 'PostController@createProductItemReviewPostWithHavingReaction'); //Done
//    Route::post('/comment', "PostController@createCommentToReviewPost"); //Done
//    Route::post('/delete_comment', "PostController@deleteCommentToReviewPost"); // Done
//});
//


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
//    Route::post('/following/{offset?}/{limit?}', 'UserPageController@following');//1.20 done
//    Route::post('/followers/{offset?}/{limit?}', 'UserPageController@followers'); //1.20 done
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

//
//// レビュー投稿
//Route::group(['prefix' => 'review_post'], function () {
//    Route::post('/check', 'PostController@isReviewPosted'); //Done - 2018 01 20
//    Route::post('/edit', 'PostController@edit'); //Done
//    Route::post('/delete', 'PostController@delete');
//    Route::post('/{reviewPostId}', 'PostController@getReviewPostDetail');
//});
//


//

//Route::group(['prefix'=> 'feeds'],function(){
//    Route::get('/detail/{feedId}','FeedsController@detail');
//    Route::get('/comments/{feedId}/{boundaryId}','FeedsController@comments'); //Done - 2018 01 20
//});