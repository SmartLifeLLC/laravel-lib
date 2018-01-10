<?php

namespace App\Console\Commands;

use App\Lib\Logger;
use App\Lib\Util;
use App\Models\JicfsCategory;
use App\Models\ProductCategory;
use Illuminate\Console\Command;
use DB;
use Mockery\Exception;

class CategoryGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:category-generator';

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

        $productCategory = new ProductCategory();
        $jicfsCategory = new JicfsCategory();
        $filePath = storage_path('app/rawdata/category_data.csv');
        if(($handler = fopen($filePath,"r"))!==false) {
            while($data = fgetcsv($handler,1000,",")) {
                try {
                    DB::beginTransaction();

                    $jicfsCategoryCode = (int) Util::removeWhite($data[0]);

                    //Create Large category
                    $largeSection = $data[2];
                    $largeSectionId = $productCategory->appendNewCategory($largeSection);

                    //Create Mediaum Category
                    $mediumSection = $data[3];
                    $mediumSectionId = $productCategory->appendNewCategory($mediumSection,$largeSectionId);


                    //Create Small Category
                    $smallSection = $data[4];
                    $smallSectionId = $productCategory->appendNewCategory($smallSection,$mediumSectionId);

                    $jicfsCategory->createCategory($jicfsCategoryCode,$smallSectionId,$largeSection,$mediumSection,$smallSection);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    throw new Exception($e);
                } finally {

                }
            }

        }else{
            echo "Failed to load file {$filePath}";
        }
    }
}
