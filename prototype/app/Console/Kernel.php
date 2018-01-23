<?php

namespace App\Console;

use App\Console\Commands\JicfsItemReader;
use App\Console\Commands\Translate\ContributionCommentTranslator;
use App\Http\Controllers\Translate\TranslateCommentController;
use App\Models\JicfsCategory;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CategoryGenerator;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CategoryGenerator::class,
        JicfsItemReader::class,
        Translate/ContributionCommentTranslator::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
