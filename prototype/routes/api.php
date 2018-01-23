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
        Route::get('/auth/get/{facebookId}', 'AuthController@getUserAuth');

        //Get user notification logs
        Route::get('/notification-log/list/{boundaryId?}','NotificationLogController@getList');

        //Switch user block status.
        //isBlockOn : {0/1}
        Route::put('/block/edit/{targetUserId}/{isBlockOn}','BlockController@switchUserBlockStatus');
        Route::get('/block/list','BlockController@getList');



        //Switch user Follow status
        Route::put('/follow/edit/{targetUserId}/{isFollowOn}','FollowController@switchUserFollowStatus');

	    //Follow and follower list
	    Route::get('/follow/list/{ownerId?}','FollowController@getFollowList');
	    Route::get('/follower/list/{ownerId?}','FollowController@getFollowerList');

		//device info for notification token
        Route::post('/device/create','DeviceController@create');


        //User setting
        Route::get('/notification-setting/list','UserController@getNotificationSettings');
	    Route::put('/notification-setting/edit','UserController@editNotificationSettings');

	    //User detail
	    Route::get('/info/get','UserController@getInfo');

	    //Edit
	    Route::put('/info/edit','UserController@editProfile');

	    //Page - base
	    Route::get('/page/get/{ownerId?}','UserController@pageInfo');

    });

Route::group(['prefix'=>'featured','namespace'=>'Featured'],
    function(){
        Route::get('/user/list','FeaturedUsersController@getList');
    });

Route::group(['prefix'=>'category'],
    function(){
        Route::get('list/{ancestorId?}','CategoryController@getList');
    });

Route::group(['prefix'=>'product'], function(){
    Route::get('list','ProductController@getList');
});



Route::group(['prefix'=>'comment'],function(){
	Route::post('create','CommentController@create');
	Route::delete('delete/{commentId}','CommentController@delete');
	Route::get('list/{contributionId}/{boundaryId}','CommentController@getList');
});


Route::group(['prefix'=>'contribution'],function(){
	Route::post('create','ContributionController@create');
	Route::put('edit/{contributionId}','ContributionController@edit');
	Route::get('check/{productId}','ContributionController@check');
	Route::get('get/{contributionId}','ContributionController@get');
	Route::get('list/{targetId}','ContributionController@list');
	Route::delete('delete/{contributionId}','ContributionController@delete');
});

Route::group(['prefix'=>'reaction'],function(){
	Route::put('edit/{isReactionOn}','ReactionController@edit');
	Route::get('list/{contributionId}','ReactionController@getList');
});

