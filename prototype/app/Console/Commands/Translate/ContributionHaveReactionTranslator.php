<?php
/**
 * Created by PhpStorm.
 * User: wada
 * Date: 2018/01/28
 * Time: 20:50
 */

namespace App\Console\Commands\Translate;

use Illuminate\Console\Command;
use App\Http\Controllers\Translate\TranslateHaveReactionController;

class ContributionHaveReactionTranslator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:contribution-have-reaction-translator';

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
        $TranslateResult = (new TranslateHaveReactionController())->translatePreviousData();
        echo $TranslateResult;
    }
}