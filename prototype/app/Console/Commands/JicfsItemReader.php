<?php

namespace App\Console\Commands;

use App\ValueObject\JicfsObject\JANProductBaseInfo;
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
        $filePath = storage_path('app/rawdata/product_item_sample.csv');
        if(($handler = fopen($filePath,"r"))!==false) {
            while($data = fgetcsv($handler,0,"\t")) {
                $data = mb_convert_encoding($data,'utf-8','sjis');
                if($data[1]=="A1"){
                    $janProductBaseInfo = new JANProductBaseInfo($data);
                    for($i=1; $i<56 ; $i++){
                        $varName = $janProductBaseInfo->getVarNameFor($i);
                        var_dump($janProductBaseInfo->{$varName});
                    }
                    exit;
                }
            }
        }
    }


}
