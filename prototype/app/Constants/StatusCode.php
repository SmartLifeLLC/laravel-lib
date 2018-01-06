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
        const    SUCCESS = "0200";                          // 成功


        /**
        * auth
        */
        const FACEBOOK_TOKEN_FAILED = "0300" ;
        const AUTH_FAILED  = "0301" ;

        const   FEED_FAILED_TO_GET_DATA = "0400";

        const UNKNOWN_USER_ID = "9300"; //User id によるユーザ確認失敗


        /**
         * 異常系
         */
        const    REQUEST_PARAMETER_ERROR = "9100";           // リクエストパラメータエラー
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


        const    FACEBOOK_TOKEN_API_ERROR = "8001";          // TOKEN API ERROR
        const    FACEBOOK_ID_DOES_NOT_MATCH = "8002";
        /**
         * 不明
         */
        const    UNKNOWN = "999999";                         // 不明
}