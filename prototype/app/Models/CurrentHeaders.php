<?php
/**
 * Created by PhpStorm.
 * User: jung
 * Date: 2018/01/24
 * Time: 11:27
 */

namespace App\Models;

use App\Lib\Singleton;

class CurrentHeaders
{
	use Singleton;
	private $headers;
	public function setHeaders($headers){
		$this->headers = $headers;
	}

	public function get($key){
		return $this->headers->get($key);
	}
}