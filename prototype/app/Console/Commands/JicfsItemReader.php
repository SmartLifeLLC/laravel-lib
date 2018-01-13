<?php

namespace App\Console\Commands;

use App\Lib\Logger;
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
        $counters = ['total'=>0,'jicfs'=>0,'product'=>0,'total_manufacturer'=>0,'manufacturer'=>0];

        if(($handler = fopen($filePath,"r"))!==false) {
            while($csvData = fgetcsv($handler,0,"\t")) {
                $csvData = mb_convert_encoding($csvData,'utf-8','sjis-win');
                $recordType = $csvData[1];

                switch ($recordType){
                    case "A1" : {


                        $janProductBaseInfoVO = new JANProductBaseInfoVO($csvData);
                        $serviceResult  = $productService->createProductAndJicfsProduct($janProductBaseInfoVO);
                        if($serviceResult == null) exit(-1);
                        $counters['total'] += 1;
                        $counters['jicfs'] += (int)($serviceResult->getResult()->isJicfsProductCreated());
                        $counters['product'] += (int)($serviceResult->getResult()->isProductCreated());


                        break;
                    }
                    case "D5":{
                        //There is two types for D5.
                        //Determine the types by 10th column is exists or not
                        if(isset($csvData['10'])){
                            $jicfsManufacturerInfoVO = new JicfsManufacturerInfoVO($csvData);
                            $serviceResult = $productService->createProductManufacturer($jicfsManufacturerInfoVO);
                            $counters['total_manufacturer'] += 1;
                            $counters['manufacturer'] += (int)($serviceResult->getResult()->isCreated());

                        }
                        break;
                    }
                }
            }
            Logger::info("CREATED PRODUCT COUNTER",$counters);
        }
    }



}
