<?php

namespace Tests;

use App\Constants\HeaderKeys;
use App\Constants\Versions;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use DB;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    protected $facebookId;
    protected $facebookToken;
    protected $userId;
    protected $auth;
    protected $headers;

    public function httpTestSetup(){
	    $this->headers[HeaderKeys::REACT_VERSION] = Versions::CURRENT;

    }
    public function setUp(){
        parent::setUp();
        $this->createApplication();
        DB::enableQueryLog();

        // Artisan::call('migrate');
    }

    protected function prepareFacebookToken(){
        $this->facebookId = "10214655735416121";
        //この値は時限があるので注意
        $this->facebookToken = "EAAEFt83nGV4BACn1kY66nefV22tYZByweasyt9grC50vUP9TSkmh2v0k2Qqzm22nBvz0mIdnIWUgx1QM3pzjLAZCT5mlRAOUFKSNAZCCtMUw3NU5pDRKLtOFuRb2HOvbU6ngFWmxsVpfVhecCnmaOtDvHPpnZBN9aVglHnm0ZBIBdFAtB82X6GJqkBaisKq0ZBlbNV2U15MJr3AKdj9RrCVL99u7GrQsEkm4OM4cWYKQZDZD";
        $this->headers[HeaderKeys::FB_TOKEN] = $this->facebookToken;
    }

    protected function prepareUserWithIdAndAuth($userId,$auth){
	    $this->userId = $userId;
	    $this->auth = $auth;
	    $this->headers[HeaderKeys::REACT_USER_ID] = $this->userId;
	    $this->headers[HeaderKeys::REACT_AUTH] = $this->auth;
    }

    protected function prepareUser(){
		$userEntity = (new User())->getRandomUser();
		$this->userId = $userEntity->id;
		$this->auth = $userEntity->auth;
	    $this->headers[HeaderKeys::REACT_USER_ID] = $this->userId;
        $this->headers[HeaderKeys::REACT_AUTH] = $this->auth;
    }


    /**
     * @param $httpMethod
     * @param $uri
     * @param $data
     * @return Array
     */
    protected function getJsonRequestContent($httpMethod, $uri, $data = []){
        return json_decode($this->json(
            $httpMethod,
            $uri,
            $data,
            $this->headers
        )->getContent(),true);
    }

    public function tearDown(){
        // Artisan::call('migrate:reset');
        parent::tearDown();
    }

    public function startTestLog($fncName)
    {
        print_r(PHP_EOL . '<---------- START phpUnit Testing ... ' .  $fncName . ' ---------->'. PHP_EOL);
    }
    public function outputLog($target)
    {
        print_r(var_export(PHP_EOL .$target.  PHP_EOL) );
    }
    public function endTestLog($fncName)
    {
        print_r(PHP_EOL . '<---------- END phpUnit Test ... ' . $fncName . ' ---------->' . PHP_EOL);
    }

    public function printSQLLog(){
        print_r(DB::getQueryLog());
    }

    protected function printResponse($response){
        echo "VERSION : " . $response["version"];
        echo "\r\n";
        echo "STATUS : " . $response["status"];
        echo "\r\n";
        echo "CODE : " . $response["code"];
        echo "\r\n";
        echo "BODY : " ;
        print_r(json_decode(base64_decode($response['body']),true));
    }


}
