<?php
/**
 * class StatusMessage
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/19
 */

namespace App\Constants;


final class StatusMessage
{
    static function get(String $code){
        switch ($code){
            case StatusCode::MAINTENANCE_MODE : return "メンテナンス中";
            case StatusCode::API_VERSION_UPDATE : return "レコミルは現在のバージョンではお使いいただけません。お手数おかけいたしますが最新バージョンにアップデートしてください。";
            case StatusCode::SUCCESS: return "0200";                      // 各情報取得
            //case StatusCode::READ_SUCCESS : return "0200";                      // 各情報取得
//            case StatusCode::LOGIN_SUCCESS : return "0210";                     // ログイン
//            case StatusCode::LOGIN_CONTINUE : return "0211";                    // ログイン継続
//            case StatusCode::LOGOUT_SUCCESS : return "0212";                    // ログアウト
//            case StatusCode::USER_LOGOUT : return "0213";                       // 強制ログアウト
//            case StatusCode::REVIEW_POST_SUCCESS : return "0220";               // レビュー投稿
//            case StatusCode::REVIEW_UPDATE_SUCCESS : return "0221";             // レビュー編集
//            case StatusCode::REVIEW_DELETE_SUCCESS : return "0222";             // レビュー削除
//            case StatusCode::COMMENT_POST_SUCCESS : return "0230";              // コメント投稿
//            case StatusCode::COMMENT_DELETE_SUCCESS : return "0231";            // コメント削除
//            case StatusCode::FOLLOW_ADD_SUCCESS : return "0240";                // フォロー追加
//            case StatusCode::FOLLOW_REMOVE_SUCCESS : return "0241";             // フォロー解除
//            case StatusCode::FOLLOWER_ADD_SUCCESS : return "0242";              // フォロワー追加
//            case StatusCode::FOLLOWER_REMOVE_SUCCESS : return "0243";           // フォロワー解除
//            case StatusCode::BLOCK_ADD_SUCCESS : return "0250";                 // ブロック追加
//            case StatusCode::BLOCK_REMOVE_SUCCESS : return "0251";              // ブロック解除
//            case StatusCode::USER_CREATE_SUCCESS : return "0260";               // 新規登録
//            case StatusCode::USER_UPDATE_SUCCESS : return "0261";               // プロフィール編集
//            case StatusCode::USER_SETTING_UPDATE_SUCCESS : return "0262";       // 通知設定編集
//            case StatusCode::REACTION_ADD_SUCCESS : return "0270";              // リアクション追加
//            case StatusCode::REACTION_REMOVE_SUCCESS : return "0271";           // リアクション解除
//            case StatusCode::DEVICE_TOKEN_CREATE_SUCCESS : return "0280";       // デバイストークン登録



            case StatusCode::FACEBOOK_TOKEN_FAILED : return "Facebook Token認証に失敗しました。";
            case StatusCode::AUTH_FAILED : return "auth 認証に失敗しました。";
            case StatusCode::FEED_FAILED_TO_GET_DATA : return "投稿詳細を取得できませんでした。";

            /**
             * 異常系
             */
            case StatusCode::REQUEST_PARAMETER_ERROR : return "パラメーターエラーです。";                     // リクエストパラメータエラー
            case StatusCode::READ_ERROR : return "9200";                        // 各情報取得エラー
            case StatusCode::USER_READ_ERROR : return "9201";                   // 会員情報取得エラー（存在しない会員ID）
            case StatusCode::LOGIN_ERROR : return "9210";                       // ログインエラー
            case StatusCode::REVIEW_POST_ERROR : return "9220";                 // レビュー投稿エラー
            case StatusCode::REVIEW_UPDATE_ERROR : return "9221";               // レビュー編集エラー
            case StatusCode::REVIEW_DELETE_ERROR : return "9222";               // レビュー削除エラー
            case StatusCode::COMMENT_POST_ERROR : return "9230";                // コメント投稿エラー
            case StatusCode::COMMENT_DELETE_ERROR : return "9231";              // コメント削除エラー
            case StatusCode::FOLLOW_ADD_ERROR : return "9240";                  // フォロー追加エラー
            case StatusCode::FOLLOW_REMOVE_ERROR : return "9241";               // フォロー解除エラー
            case StatusCode::FOLLOWER_ADD_ERROR : return "9242";                // フォロワー追加エラー
            case StatusCode::FOLLOWER_REMOVE_ERROR : return "9243";             // フォロワー解除エラー
            case StatusCode::BLOCK_ADD_ERROR : return "9250";                   // ブロック追加エラー
            case StatusCode::BLOCK_REMOVE_ERROR : return "9251";                // ブロック削除エラー
            case StatusCode::USER_CREATE_ERROR : return "9260";                 // 新規登録エラー
            case StatusCode::USER_UPDATE_ERROR : return "9261";                 // プロフィール編集エラー
            case StatusCode::USER_SETTING_UPDATE_ERROR : return "9262";         // 通知設定編集エラー
            case StatusCode::REACTION_ADD_ERROR : return "9270";                // リアクション追加エラー
            case StatusCode::REACTION_REMOVE_ERROR : return "9271";             // リアクション解除エラー
            case StatusCode::DEVICE_TOKEN_CREATE_ERROR : return "9280";         // デバイストークン登録エラー
            case StatusCode::USER_BLOCKED : return "認証されていないユーザです。";         // デバイストークン登録エラー


            case StatusCode::FACEBOOK_TOKEN_API_ERROR : return "Facebook ログインエラー";
            case StatusCode::FACEBOOK_ID_DOES_NOT_MATCH : return "Facebook ID 認証エラー";
            /**
             * 不明
             */

            case StatusCode::UNKNOWN : return "999999";      // 不明
            default:return "999999";                         // 不明
        }
    }
}