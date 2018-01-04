<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/04
 * Time: 18:38
 */
namespace App\Http\JsonView\User\Follow;

use App\Http\JsonView\JsonResponseView;
class SwitchUserFollowStatusJsonView extends JsonResponseView
{
    /**
     * @var \App\ValueObject\SwitchFollowResultVO
     */
    protected $data;
    function createBody()
    {
        if($this->data->isFollowOn()){
            $message = "フォローに成功しました。";
        }else{
            $message = "フォロー解除しました。";
        }
        $this->body = ['message'=>$message];
    }
}