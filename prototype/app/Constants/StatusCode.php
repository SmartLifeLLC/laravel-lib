<?php
/**
 * class ServiceStatus
 * @package App\Constants
 * @Author : Sinyu Jung
 * Copyright :  WonderPlanet Inc. All rights reserved.
 * Last Modified  2017/11/19
 */

namespace App\Constants;


use App\Lib\Enum;

final class StatusCode
{
        const   SERVICE_SUCCESS = "0000";
        const   SERVICE_FAILED = "0001";



        const STATUS_OK_OLD_CODE = "OK";
        const STATUS_ERROR_OLD_CODE = "ERROR";

        /**
         * 正常系
         * システム
         */
        const    MAINTENANCE_MODE = "0100";                  // メンテナンスモード
        const    API_VERSION_UPDATE = "0101";                // APIバージョンアップ

        /**
         * 正常系
         * アプリ（サーバーサイド）
         */
        const    READ_SUCCESS = "0200";                      // 各情報取得
        const    LOGIN_SUCCESS = "0210";                     // ログイン
        const    LOGIN_CONTINUE = "0211";                    // ログイン継続
        const    LOGOUT_SUCCESS = "0212";                    // ログアウト
        const    USER_LOGOUT = "0213";                       // 強制ログアウト
        const    REVIEW_POST_SUCCESS = "0220";               // レビュー投稿
        const    REVIEW_UPDATE_SUCCESS = "0221";             // レビュー編集
        const    REVIEW_DELETE_SUCCESS = "0222";             // レビュー削除
        const    COMMENT_POST_SUCCESS = "0230";              // コメント投稿
        const    COMMENT_DELETE_SUCCESS = "0231";            // コメント削除
        const    FOLLOW_ADD_SUCCESS = "0240";                // フォロー追加
        const    FOLLOW_REMOVE_SUCCESS = "0241";             // フォロー解除
        const    FOLLOWER_ADD_SUCCESS = "0242";              // フォロワー追加
        const    FOLLOWER_REMOVE_SUCCESS = "0243";           // フォロワー解除
        const    BLOCK_ADD_SUCCESS = "0250";                 // ブロック追加
        const    BLOCK_REMOVE_SUCCESS = "0251";              // ブロック解除
        const    USER_CREATE_SUCCESS = "0260";               // 新規登録
        const    USER_UPDATE_SUCCESS = "0261";               // プロフィール編集
        const    USER_SETTING_UPDATE_SUCCESS = "0262";       // 通知設定編集
        const    REACTION_ADD_SUCCESS = "0270";              // リアクション追加
        const    REACTION_REMOVE_SUCCESS = "0271";           // リアクション解除
        const    DEVICE_TOKEN_CREATE_SUCCESS = "0280";       // デバイストークン登録


        /**
        * auth
        */
        const FACEBOOK_TOKEN_FAILED = "0300" ;
        const AUTH_FAILED  = "0301" ;

        const   FEED_FAILED_TO_GET_DATA = "0400";

        /**
         * 異常系
         */
        const    REQUEST_ERROR = "9100";                     // リクエストパラメータエラー
        const    READ_ERROR = "9200";                        // 各情報取得エラー
        const    USER_READ_ERROR = "9201";                   // 会員情報取得エラー（存在しない会員ID）
        const    LOGIN_ERROR = "9210";                       // ログインエラー
        const    REVIEW_POST_ERROR = "9220";                 // レビュー投稿エラー
        const    REVIEW_UPDATE_ERROR = "9221";               // レビュー編集エラー
        const    REVIEW_DELETE_ERROR = "9222";               // レビュー削除エラー
        const    COMMENT_POST_ERROR = "9230";                // コメント投稿エラー
        const    COMMENT_DELETE_ERROR = "9231";              // コメント削除エラー
        const    FOLLOW_ADD_ERROR = "9240";                  // フォロー追加エラー
        const    FOLLOW_REMOVE_ERROR = "9241";               // フォロー解除エラー
        const    FOLLOWER_ADD_ERROR = "9242";                // フォロワー追加エラー
        const    FOLLOWER_REMOVE_ERROR = "9243";             // フォロワー解除エラー
        const    BLOCK_ADD_ERROR = "9250";                   // ブロック追加エラー
        const    BLOCK_REMOVE_ERROR = "9251";                // ブロック削除エラー
        const    USER_BLOCKED = "9252";                      // ブロックされている
        const    USER_CREATE_ERROR = "9260";                 // 新規登録エラー
        const    USER_UPDATE_ERROR = "9261";                 // プロフィール編集エラー
        const    USER_SETTING_UPDATE_ERROR = "9262";         // 通知設定編集エラー
        const    REACTION_ADD_ERROR = "9270";                // リアクション追加エラー
        const    REACTION_REMOVE_ERROR = "9271";             // リアクション解除エラー
        const    DEVICE_TOKEN_CREATE_ERROR = "9280";         // デバイストークン登録エラー
        const    BLOCKED_USER_ERROR = "9290";                // ブロックユーザ



        /**
         * 不明
         */
        const    UNKNOWN = "999999";                         // 不明
}