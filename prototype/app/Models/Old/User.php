<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/29
 * Time: 22:41
 */

namespace App\Models\Old;

use Illuminate\Database\Eloquent\Model;
use App\Models\DBModel;

class User extends DBModel
{
    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getData()
    {
        $data = DB::connection('mysql_old')
            ->table('users')
            ->select('id',
                'facebook_id',
                'mail_address',
                'auth',
                'first_name',
                'family_name',
                'user_name',
                'birthday',
                'address',
                'gender',
                'gender_published_flag',
                'birthday_published_flag',
                'worked_history',
                'profile_image_id',
                'cover_image_id',
                'description',
                'created_at',
                'updated_at'
                )
            ->get();

        return $data;
    }
}