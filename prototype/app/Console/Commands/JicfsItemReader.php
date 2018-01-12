<?php

namespace App\Console\Commands;

use App\Services\ProductService;
use App\ValueObject\JicfsObject\JANProductBaseInfoVO;
use App\ValueObject\JicfsObject\JicfsManufacturerInfoVO;
use Illuminate\Console\Command;

class JicfsItemReader extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:read-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $productService = new ProductService();
        $filePath = storage_path('app/rawdata/product_item_sample.csv');
        if(($handler = fopen($filePath,"r"))!==false) {
            $count = 0;
            while($csvData = fgetcsv($handler,0,"\t")) {
                $csvData = mb_convert_encoding($csvData,'utf-8','sjis-win');
                $recordType = $csvData[1];

                switch ($recordType){
                    case "A1" : {
                        $janProductBaseInfoVO = new JANProductBaseInfoVO($csvData);
                        $productService->createProductAndJicfsProduct($janProductBaseInfoVO);
                        $count ++;
                        $this->consoleLog("#{$count} : A1 - ".$janProductBaseInfoVO->_14_productNameKanji);
                        break;
                    }
                    case "D5":{
                        //There is two types for D5.
                        //Determine by 10th column exists or not
                        if(isset($csvData['10'])){
                            $jicfsManufacturerInfoVO = new JicfsManufacturerInfoVO($csvData);
                            $productService->createProductBrand($jicfsManufacturerInfoVO);
                            $count ++;
                            $this->consoleLog("#{$count} : A1 - ".$jicfsManufacturerInfoVO->_7_companyName);
                        }
                        break;
                    }
                }
            }
        }
    }

    private function consoleLog($log){

    }

}
