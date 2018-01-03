<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use DB;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(){
        parent::setUp();
        $this->createApplication();
        DB::enableQueryLog();

        // Artisan::call('migrate');
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

}
