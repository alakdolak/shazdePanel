<?php

namespace App\Console;

use App\Http\Controllers\BackupController;
use App\models\Backup;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

include_once __DIR__ . '/../Http/Controllers/Common.php';

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Backup'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {

        $backups = DB::connection('mysql2')->select('select * from backup WHERE 1 = 2');

        foreach ($backups as $itr) {
            switch ($itr->_interval_) {
                case getValueInfo('5_min'):
                    $schedule->command('backup:all ' . $itr->id)->everyFiveMinutes();
                    break;
                case getValueInfo('10_min'):
                    $schedule->command('backup:all ' . $itr->id)->everyTenMinutes();
                    break;
                case getValueInfo('15_min'):
                    $schedule->command('backup:all ' . $itr->id)->everyFifteenMinutes();
                    break;
                case getValueInfo('30_min'):
                    $schedule->command('backup:all ' . $itr->id)->everyThirtyMinutes();
                    break;
                case getValueInfo('hour'):
                    $schedule->command('backup:all ' . $itr->id)->hourly();
                    break;
                case getValueInfo('day'):
                    $schedule->command('backup:all ' . $itr->id)->daily();
                    break;
                case getValueInfo('week'):
                    $schedule->command('backup:all ' . $itr->id)->weekly();
                    break;
                case getValueInfo('month'):
                    $schedule->command('backup:all ' . $itr->id)->monthly();
                    break;
            }

        }
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
