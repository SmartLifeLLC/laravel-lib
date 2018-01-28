<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 21:36
 */

namespace App\Models\Old;


use Illuminate\Database\Eloquent\Model;
use App\Models\DBModel;

class ReactionLog extends DBModel
{
    protected $guarded = [];


    /**
     * @return mixed
     */
    public function getData()
    {
        $data = DB::connection('mysql_old')
            ->table('ReactionLog')
            ->select('user_id', 'feed_id', 'review_post_reaction_type', 'created_at')
            ->get();

        return $data;
    }

    /**
     * @return mixed
     */
    public function getLikeData()
    {
        $likeData = DB::connection('mysql_old')
            ->table('ReactionLog')
            ->where('review_post_reaction_type', 1)
            ->select('user_id', 'feed_id', 'created_at')
            ->get();

        return $likeData;
    }

    /**
     * @return mixed
     */
    public function getInterestData()
    {
        $interestData = DB::connection('mysql_old')
            ->table('ReactionLog')
            ->where('review_post_reaction_type', 2)
            ->select('user_id', 'feed_id', 'created_at')
            ->get();

        return $interestData;
    }

    /**
     * @return mixed
     */
    public function getHaveData()
    {
        $haveData = DB::connection('mysql_old')
            ->table('ReactionLog')
            ->where('review_post_reaction_type', 3)
            ->select('user_id', 'feed_id', 'created_at')
            ->get();

        return $haveData;
    }
}