<?php
	/**
	 * Created by PhpStorm.
	 * User: jung
	 * Date: 2018/01/16
	 * Time: 23:49
	 */

	namespace App\Constants;


	class UserLimitationLevel
	{
		const NO_LIMIT      = 0;
		const LIMITED_ALL        = 1;
		const LIMITED_POST       = 2;
		const LIMITED_PUT        = 3;
		const LIMITED_POST_PUT   = 4;

	}