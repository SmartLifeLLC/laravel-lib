<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 21:36
 */

namespace App\Models\Old;


use App\Constants\ContributionReactionType;

class ReactionLog extends OldModel
{
    protected $table = 'reaction_log';


    /**
     * @return mixed
     */
    public function getData()
    {
        $data = $this
            ->where('review_post_reaction_type', '!=', ContributionReactionType::HAVE)
            ->select('reaction_user_id', 'review_post_id', 'review_post_reaction_type', 'created_at')
            ->get();

        return $data;
    }

    /**
     * @return mixed
     */
    public function getLikeData()
    {
        $likeData = $this
            ->where('review_post_reaction_type', ContributionReactionType::LIKE)
            ->select('reaction_user_id', 'review_post_id', 'created_at')
            ->get();

        return $likeData;
    }

    /**
     * @return mixed
     */
    public function getInterestData()
    {
        $interestData = $this
            ->where('review_post_reaction_type', ContributionReactionType::INTEREST)
            ->select('reaction_user_id', 'review_post_id', 'created_at')
            ->get();

        return $interestData;
    }

    /**
     * @return mixed
     */
    public function getHaveData()
    {
        $haveData = $this
            ->where('review_post_reaction_type', ContributionReactionType::HAVE)
            ->select('reaction_user_id', 'review_post_id', 'created_at')
            ->get();

        return $haveData;
    }
}